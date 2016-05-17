<?php

namespace Frcho\Bundle\CrontaskBundle\Util;

use Frcho\Bundle\CrontaskBundle\Entity As Entity;

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

    function setArrayCommands(array $param) {
        foreach ($param as $param) {

            $prev = $this->em->getRepository('FrchoCrontaskBundle:CronTask')
                    ->findOneBy(array('name' => $param['name'], 'commands' => $param['commands']));
            if (!$prev) {
                
                if (isset($param['range']) && $param['range'] == Entity\CronTask::MINUTES) {
                    $range = Entity\CronTask::MINUTES;
                } elseif (isset($param['range']) && $param['range'] ==  Entity\CronTask::HOURS) {
                    $range = Entity\CronTask::HOURS;
                } elseif (isset($param['range']) && $param['range'] == Entity\CronTask::DAYS) {
                    $range = Entity\CronTask::DAYS;
                }

                $interval = Util::convertDaysHoursMinutes($param['interval'], $range);
               
                $cronTask = new Entity\CronTask();
                $cronTask->setName($param['name']);
                $cronTask->setRange($range);
                $cronTask->setInterval($interval);
                $cronTask->setCommands($param['commands']);
                $cronTask->setStatusTask($param['enabled']);

                $this->em->persist($cronTask);
            }
             
            $this->em->flush();
        }
    }
}
