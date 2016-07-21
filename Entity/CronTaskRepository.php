<?php

namespace Frcho\Bundle\CrontaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Cron Task service repository
 *
 * @author Luis Fernando Granados <lgranados@kijho> 02/02/2016
 */
class CronTaskRepository extends EntityRepository {

    /**
     * Esta función nos arroja las tareas que estan activas y pueden ser ejecutadas
     * @author Luis Fernando Granados <lgranados@kijho.com> 01/25/2016
     * @return array
     */
    public function tasksActive() {

        $em = $this->getEntityManager();
        $consult = $em->createQuery("
        SELECT ct FROM FrchoCrontaskBundle:CronTask ct
        WHERE ct.statusTask = :statusTask ");
        $consult->setParameter('statusTask', CronTask::ENABLED);
        return $consult->getResult();
    }

}

?>
