<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class SynchroJob {

    /** @var integer */
    private $id;
    /** @var string */
    private $url;
    /** @var string */
    private $key;
    /** @var string */
    private $entityClass;
    /** @var string */
    private $table;
    /** @var string */
    private $description;
    /** @var string */
    private $wsAttributes;
    /** @var string */
    private $dbAttributes;
    /** @var string */
    private $enAttributes;

    /** @var ArrayCollection (SynchroLog) */
    private $logs;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SynchroJob
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return SynchroJob
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return SynchroJob
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     * @return SynchroJob
     */
    public function setEntityClass(string $entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return SynchroJob
     */
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return SynchroJob
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return SynchroLog[]
     */
    public function getLogs()
    {
        return $this->logs->toArray();
    }

    /**
     * @return SynchroLog
     */
    public function getDernierLog()
    {
        $last = null;
        /** @var SynchroLog $log */
        foreach ($this->logs as $log) {
            if ($last === null)
                $last = $log;
            else {
                $last_date = $last->getDate()->format('Y/m/d H:i:s');
                $log_date  = $log->getDate()->format('Y/m/d H:i:s');
                if($last_date < $log_date)
                    $last = $log;
            }
        }
        return $last;
    }

    /**
     * @return string
     */
    public function getWsAttributes()
    {
        return $this->wsAttributes;
    }

    /**
     * @param string $wsAttributes
     * @return SynchroJob
     */
    public function setWsAttributes(string $wsAttributes)
    {
        $this->wsAttributes = $wsAttributes;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbAttributes()
    {
        return $this->dbAttributes;
    }

    /**
     * @param string $dbAttributes
     * @return SynchroJob
     */
    public function setDbAttributes(string $dbAttributes)
    {
        $this->dbAttributes = $dbAttributes;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnAttributes()
    {
        return $this->enAttributes;
    }

    /**
     * @param string $enAttributes
     * @return SynchroJob
     */
    public function setEnAttributes(string $enAttributes)
    {
        $this->enAttributes = $enAttributes;
        return $this;
    }

}