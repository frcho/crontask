<?php

namespace Frcho\Bundle\CrontaskBundle\Command\ScheduleTask;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando que carga los crons por valores por defecto que tiene la aplicacion
 * @author Luis Fernando Granados <lgranados@kijho.com>
 * @since 1.0 01/02/2016
 */
class CronTasksDefaultCommand extends Command {

    protected function configure() {

        $this->setName('crontasks:default')->setDescription('Creates the commands by default in database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $container = $this->getApplication()->getKernel()->getContainer();
        $defaultCommands = array(
            array("name" => "Example asset symlinking task",
                "interval" => 2 /* Run once every 2 minutes */,
                "range" => 'minutes',
                "commands" => 'assets:install --symlink web',
                "enabled" => true
            ),
            array("name" => "Example asset  task",
                "interval" => 2 /* Run once every 2 hour */,
                "range" => 'hours',
                "commands" => 'cache:clear',
                "enabled" => false
            ),
        );

        $container->get('frcho.crontask_default')->setArrayCommands($defaultCommands);
    }

}

