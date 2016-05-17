<?php

namespace Frcho\Bundle\CrontaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="cron_task")
 * @UniqueEntity("name")
 */
class CronTask {

    const DAYS = 'days';
    const HOURS = 'hours';
    const MINUTES = 'minutes';
    const DISABLED = false;
    const ENABLED = true;

    /**
     * @ORM\Id
     * @ORM\Column(name="crontask_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="crontask_name", type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="crontask_range", type="string")
     */
    private $range;

    /**
     * @ORM\Column(name="crontask_command", type="array")
     */
    private $commands;

    /**
     * @ORM\Column(name="crontask_interval", type="integer")
     */
    private $interval;

    /**
     * @ORM\Column(name="crontask_lastrun", type="datetime", nullable=true)
     */
    private $lastrun;

    /**
     * @ORM\Column(name="crontask_status", type="boolean", nullable=true)
     */
    private $statusTask;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getCommands() {
        return $this->commands;
    }

    public function setCommands($commands) {
        $this->commands = $commands;
        return $this;
    }

    public function getInterval() {
        return $this->interval;
    }

    public function setInterval($interval) {
        $this->interval = $interval;
        return $this;
    }

    public function getLastRun() {
        return $this->lastrun;
    }

    public function setLastRun($lastrun) {
        $this->lastrun = $lastrun;
        return $this;
    }

    function getRange() {
        return $this->range;
    }

    function setRange($range) {
        $this->range = $range;
    }

    function getStatusTask() {
        return $this->statusTask;
    }

    function setStatusTask($statusTask) {
        $this->statusTask = $statusTask;
    }

    public function __toString() {
        return $this->getName();
    }

}
