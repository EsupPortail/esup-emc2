<?php

namespace Application\Entity\Db;

use Doctrine\ORM\EntityManager;
use Utilisateur\Entity\Db\User;
use Utilisateur\Entity\HistoriqueAwareTrait;

class ValidationDemande {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var ValidationType */
    private $type;
    /** @var string */
    private $entity;
    /** @var string */
    private $objectId;
    /** @var User */
    private $validateur;
    /** @var Validation */
    private $validation;

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
     * @return ValidationDemande
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     * @return ValidationDemande
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     * @return ValidationDemande
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
        return $this;
    }

    /**
     * @param EntityManager $entityManager
     * @return object
     */
    public function getObject(EntityManager $entityManager) {
        return $entityManager->getRepository($this->getEntity())->find($this->getObjectId());
    }

    /**
     * @return User
     */
    public function getValidateur()
    {
        return $this->validateur;
    }

    /**
     * @param User $validateur
     * @return ValidationDemande
     */
    public function setValidateur($validateur)
    {
        $this->validateur = $validateur;
        return $this;
    }

    /**
     * @return Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param Validation $validation
     * @return ValidationDemande
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
        return $this;
    }

}

