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
    /** @var string */
    private $sql;

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
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param string $sql
     * @return SynchroLog
     */
    public function setSql(string $sql)
    {
        $this->sql = $sql;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $text  = "<strong>Rapport de synchronisation</strong><br/>";
        $text .= "<strong>[".$this->getDate()->format('d/m/Y à H:i:s')."] </strong><br><br>";
        $text .= "<p>" . $this->getRapport() . "</p>";
        return $text;
    }
}