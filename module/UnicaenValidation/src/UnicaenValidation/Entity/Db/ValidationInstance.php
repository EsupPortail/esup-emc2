<?php

namespace UnicaenValidation\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\Db\ValidationType;

class ValidationInstance {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var ValidationType */
    private $type;
    /** @var string */
    private $valeur;
    /** @var string */
    private $entityClass;
    /** @var string */
    private $entityId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ValidationType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationInstance
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param string $valeur
     * @return ValidationInstance
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
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
     * @return ValidationInstance
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param string $entityId
     * @return ValidationInstance
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }
}