<?php

namespace Application\Entity\Db;

use Utilisateur\Entity\HistoriqueAwareTrait;

class ConfigurationFicheMetier {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $operation;
    /** @var string */
    private $entityType;
    /** @var string */
    private $entityId;

    private $entity;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $operation
     * @return ConfigurationFicheMetier
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @param string $entityType
     * @return ConfigurationFicheMetier
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
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
     * @return ConfigurationFicheMetier
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return ConfigurationFicheMetier
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

}