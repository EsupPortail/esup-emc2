<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Corps {

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection (Grade) */
    private $grades;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Corps
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Corps
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
     * @return Corps
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Grade[]
     */
    public function getGrades()
    {
        return $this->grades->toArray();
    }

    /**
     * @param Grade $grade
     * @return Corps
     */
    public function addGrade($grade)
    {
        $this->grades->add($grade);
        return $this;
    }

    /**
     * @param Grade $grade
     * @return Corps
     */
    public function removeGrade($grade)
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "(".$this->getCode().") ".$this->getLibelle();
    }


}