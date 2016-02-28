<?php

namespace Frcho\Bundle\CrontaskBundle\Command\ScheduleTask;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Frcho\Bundle\CrontaskBundle\Entity as Entity;
use Frcho\Bundle\CrontaskBundle\Util\Util;

/**
 * Comando que carga los crons por valores por defecto que tiene la aplicacion
 * @author Luis Fernando Granados <lgranados@kijho.com>
 * @since 1.0 01/02/2016
 */
class CronTasksDefaultCommand extends ContainerAwareCommand {

    protected function configure() {

        $this->setName('crontasks:default')->setDescription('Creates the commands by default in database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $container = $this->getContainer();
        $defaultCommands = array(
            array("name" => "Example asset symlinking task",
                "interval" => 120 /* Run once every 2 minutes */,
                "range" => 'minutes',
                "commands" => 'assets:install:dte -- web',
                "enabled" => FALSE
            ),
            array("name" => "Example asset  task",
                "interval" => 3600 /* Run once every hour */,
                "range" => 'hours',
                "commands" => 'assets --symlink web',
                "enabled" => true
            ),
        );

        $container->get('frcho.crontask_default')->setArrayCommands($defaultCommands);
    }

}
