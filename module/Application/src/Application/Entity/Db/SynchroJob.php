<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class SynchroJob {

    /** @var integer */
    private $id;
    /** @var string */
    private $url;
    /** @var string */
    private $entityClass;
    /** @var string */
    private $key;
    /** @var string */
    private $description;

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
            if ($last === null OR $last->getDate() < $log->getDate()) $last = $log;
        }
        return $log;
    }
}