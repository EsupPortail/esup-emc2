<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class FamilleProfessionnelle {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;

    /** @var ArrayCollection */
    private $domaines;

    /**
     * MetierFamille constructor.
     */
    public function __construct()
    {
        $this->domaines = new ArrayCollection();
    }

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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return FamilleProfessionnelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getDomaines()
    {
        return $this->domaines->toArray();
    }

    /**
     * @param Domaine $domaine
     * @return FamilleProfessionnelle
     */
    public function addDomaine($domaine)
    {
        $this->domaines->add($domaine);
        return $this;
    }

    /**
     * @param Domaine $domaine
     * @return FamilleProfessionnelle
     */
    public function removeDomaine($domaine)
    {
        $this->domaines->removeElement($domaine);
        return $this;
    }
}