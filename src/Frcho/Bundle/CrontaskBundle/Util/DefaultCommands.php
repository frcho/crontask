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

                if (isset($param['range']) && $param['range'] = Entity\CronTask::MINUTES) {
                    $range = $param['range'];
                } elseif (isset($param['range']) && $param['range'] = Entity\CronTask::HOURS) {
                    $range = $param['range'];
                } elseif (isset($param['range']) && $param['range'] = Entity\CronTask::DAYS) {
                    $range = $param['range'];
                }

                $interval = $this->convertDaysHoursMinutes($param['interval'], $range);

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

    /**
     * 
     * @param type $time el tiempo que se quiere calcular siendo este dias, minutos, horas
     * @param type $type el tipo para el que se calcula el tiempo (h, d, m)
     * @param type $isSecondsToRealTime variable boolean para definir si se calcula para guardar o mostrar, por defecto es false e indica que se calcula para guardar
     * @return type String
     */
    public static function convertDaysHoursMinutes($time, $type, $isSecondsToRealTime = false) {

        $dayInSeconds = 86400;
        $hoursInSeconds = 3600;
        $minutesInSeconds = 60;
        /**
         * En la primera entrada se calcula el tiempo para mostrar en la interfaz el puesto por usuario
         * en el else se calcula el tiempo ingresado por el usuario para guardarlo en segundos
         */
        $result = null;
        if ($isSecondsToRealTime) {
            if ($type == "crontask.days" || $type == "days") {
                $result = $time / $dayInSeconds;
            }
            if ($type == "crontask.hours" || $type == "hours") {
                $result = $time / $hoursInSeconds;
            }
            if ($type == "crontask.minutes" || $type == "minutes") {
                $result = $time / $minutesInSeconds;
            }
        } else {
            if ($type == "crontask.days" || $type == "days") {
                $result = $time * $dayInSeconds / 1;
            }
            if ($type == "crontask.hours" || $type == "hours") {
                $result = $time * $hoursInSeconds / 1;
            }
            if ($type == "crontask.minutes" || $type == "minutes") {
                $result = $time * $minutesInSeconds / 1;
            }
        }

        return $result;
    }

}
