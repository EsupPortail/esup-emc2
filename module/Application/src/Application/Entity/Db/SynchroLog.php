<?php

namespace Application\Entity\Db;

use DateTime;

class SynchroLog {

    /** @var integer */
    private $id;
    /** @var SynchroJob */
    private $job;
    /** @var DateTime */
    private $date;
    /** @var string*/
    private $rapport;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SynchroLog
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return SynchroJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param SynchroJob $job
     * @return SynchroLog
     */
    public function setJob(SynchroJob $job)
    {
        $this->job = $job;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return SynchroLog
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getRapport()
    {
        return $this->rapport;
    }

    /**
     * @param string $rapport
     * @return SynchroLog
     */
    public function setRapport(string $rapport)
    {
        $this->rapport = $rapport;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $text  = "<strong>Rapport de synchronisation [".$this->getJob()->getKey()." à ".$this->getDate()->format('d/m/Y à H:i:s')."] </strong><br>";
        $text .= "<p>" . $this->getRapport() . "</p>";
        return $text;
    }
}