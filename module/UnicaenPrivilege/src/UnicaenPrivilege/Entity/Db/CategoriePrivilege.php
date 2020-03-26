<?php

namespace UnicaenPrivilege\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class CategoriePrivilege
{
    /**@var int */
    private $id;
    /**@var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var string */
    private $namespace;
    /** @var int */
    private $ordre;
    /** @var ArrayCollection */
    private $privileges;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->privileges = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return CategoriePrivilege
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return CategoriePrivilege
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return CategoriePrivilege
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return CategoriePrivilege
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @param Privilege $privilege
     * @return CategoriePrivilege
     */
    public function addPrivilege(Privilege $privilege)
    {
        $this->privileges->add($privilege);
        return $this;
    }

    /**
     * @param Privilege $privilege
     * @return CategoriePrivilege
     */
    public function removePrivilege(Privilege $privilege)
    {
        $this->privileges->removeElement($privilege);
        return $this;
    }

    /**
     * @return Privilege[]
     */
    public function getPrivilege()
    {
        return $this->privileges->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getClassname()
    {
        return ucfirst(strtolower($this->getCode())) . "Privileges";
    }
}