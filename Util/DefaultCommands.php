<?php

namespace Frcho\Bundle\CrontaskBundle\Util;

use Frcho\Bundle\CrontaskBundle\Entity As Entity;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommands {

    protected $em;
    protected $container;
    protected $security;
    protected $arrayCommands;

    /**
     * @author frcho <luisfer1g@gmail.com> 29/02/2016
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    function __construct($entityManager, $container) {
        $this->em = $entityManager;
        $this->container = $container;
    }

    function setArrayCommands(array $params) {

        foreach ($params as $param) {

            $prev = $this->em->getRepository('FrchoCrontaskBundle:CronTask')
                    ->findOneBy(array('name' => $param['name'], 'commands' => $param['commands']));

            $range = $this->range($param);
            $interval = Util::convertDaysHoursMinutes($param['interval'], $range);

            if (!empty($prev) && $prev->getIsHide() == true && $prev->getInterval() != $param['interval'] || $prev->getRange() != $param['range'] ) {
                $range = $this->range($param);
                $interval = Util::convertDaysHoursMinutes($param['interval'], $range);
                $prev->setRange($range);
                $prev->setInterval($interval);
                $this->em->persist($prev);
            }
            
            if (!$prev) {

                $range = $this->range($param);
                $interval = Util::convertDaysHoursMinutes($param['interval'], $range);
                $cronTask = new Entity\CronTask();
                $cronTask->setName($param['name']);
                $cronTask->setRange($range);
                $cronTask->setInterval($interval);
                $cronTask->setCommands($param['commands']);
                $cronTask->setStatusTask($param['enabled']);
                if (isset($param['isHide'])) {
                    $cronTask->setIsHide($param['isHide']);
                }
                $this->em->persist($cronTask);
            }
            $this->em->flush();
        }
    }

    public function range($param) {
        if (isset($param['range']) && $param['range'] == Entity\CronTask::MINUTES) {
            $range = Entity\CronTask::MINUTES;
        } elseif (isset($param['range']) && $param['range'] == Entity\CronTask::HOURS) {
            $range = Entity\CronTask::HOURS;
        } elseif (isset($param['range']) && $param['range'] == Entity\CronTask::DAYS) {
            $range = Entity\CronTask::DAYS;
        }
        return $range;
    }

}
