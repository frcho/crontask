<?php

namespace Frcho\Bundle\CrontaskBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class CronTasksRunCommand extends ContainerAwareCommand {

    private $output;

    protected function configure() {
        $this
                ->setName('crontasks:run')
                ->setDescription('Runs Cron Tasks if needed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->output = $output;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $translator = $this->getContainer()->get('translator');
        $crontasks = $em->getRepository('FrchoCrontaskBundle:CronTask')->tasksActive();

        $size = count($crontasks);

        if ($size > 0) {
            $output->writeln('<comment>Running Cron Tasks...</comment>');

            foreach ($crontasks as $crontask) {
                // Get the last run time of this task, and calculate when it should run next
                $lastrun = $crontask->getLastRun() ? $crontask->getLastRun()->format('U') : 0;
                $nextrun = $lastrun + $crontask->getInterval();

                // We must run this task if:
                // * $time is larger or equal to $nextrun
                $time = new \DateTime();

                $timestamp = $time->getTimestamp();

                $run = ($timestamp >= $nextrun);
                if ($run) {
                    $output->writeln(sprintf('Running Cron Task <info>%s</info>', $translator->trans($crontask)));

                    // Set $lastrun for this crontask
                    $crontask->setLastRun(new \DateTime());

                    try {
                        $command = $crontask->getCommands();

                        $output->writeln(sprintf('Executing command <comment>%s</comment>...', $command));

                        // Run the command
                        $this->runCommand($command);
//                }
                    } catch (\Exception $e) {
                        $output->writeln('<error>ERROR</error>' . $e);
                    }
                    // Persist crontask
                    $em->persist($crontask);
                } else {
                    $output->writeln(sprintf('Skipping Cron Task <info>%s</info>', $translator->trans($crontask)));
                }
            }
            // Flush database changes
            $em->flush();
        }
    }

    private function runCommand($string) {
        // Split namespace and arguments
        $namespace = split(' ', $string)[0];

        // Set input
        $command = $this->getApplication()->find($namespace);
        $input = new StringInput($string);

        // Send all output to the console
        $returnCode = $command->run($input, $this->output);

        return $returnCode != 0;
    }

}
