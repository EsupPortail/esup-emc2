<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Validation {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var ValidationType */
    private $type;
    /** @var ValidationValeur */
    private $valeur;
    /** @var string */
    private $commentaire;
    /** @var string */
    private $objectId;

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
     * @return Validation
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return ValidationValeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param ValidationValeur $valeur
     * @return Validation
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     * @return Validation
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
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
     * @param string $object_id
     * @return Validation
     */
    public function setObjectId($object_id)
    {
        $this->objectId = $object_id;
        return $this;
    }

}

