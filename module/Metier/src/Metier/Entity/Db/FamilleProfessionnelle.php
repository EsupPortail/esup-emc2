<?php

namespace Metier\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class FamilleProfessionnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;

    /** @var ArrayCollection (Domaine)*/
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
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return FamilleProfessionnelle
     */
    public function setLibelle(?string $libelle) : FamilleProfessionnelle
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Domaine[]
     */
    public function getDomaines() : array
    {
        return $this->domaines->toArray();
    }

    /**
     * @param Domaine $domaine
     * @return FamilleProfessionnelle
     */
    public function addDomaine(Domaine $domaine) : FamilleProfessionnelle
    {
        $this->domaines->add($domaine);
        return $this;
    }

    /**
     * @param Domaine $domaine
     * @return FamilleProfessionnelle
     */
    public function removeDomaine(Domaine $domaine) : FamilleProfessionnelle
    {
        $this->domaines->removeElement($domaine);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getLibelle();
    }
}