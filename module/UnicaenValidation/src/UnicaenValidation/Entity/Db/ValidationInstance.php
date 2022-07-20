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
     * @param string|null $valeur
     * @return ValidationInstance
     */
    public function setValeur(?string $valeur)
    {
        $this->valeur = $valeur;
        return $this;
    }

    /**
     * @param Object $entity
     * @return ValidationInstance
     */
    public function setEntity($entity) {
        $this->entityClass = get_class($entity);
        $this->entityId = $entity->getId();
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

    public function generateTooltipText()
    {
        $text = "";
        $text .= "Validation effectuée<br/>";
        $text .= "par <span class='user'>".$this->histoModificateur->getDisplayName()."</span><br/>";
        $text .= "le <span class='date'>".$this->getHistoModification()->format('d/m/Y')."</span>";
        return $text;
    }
}