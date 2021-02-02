<?php

namespace EntretienProfessionnel\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Campagne implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $annee;
    /** @var DateTime */
    private $dateDebut;
    /** @var DateTime */
    private $dateFin;
    /** @var Campagne */
    private $precede;
    /** @var ArrayCollection (EntretienProfessionnel) */
    private $entretiens;

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string|null (par exemple : 2019/2020)
     */
    public function getAnnee() : ?string
    {
        return $this->annee;
    }

    /**
     * @param string|null $annee (par exemple : 2019/2020)
     * @return Campagne
     */
    public function setAnnee(?string $annee) : Campagne
    {
        $this->annee = $annee;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateDebut() : ?DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime|null $dateDebut
     * @return Campagne
     */
    public function setDateDebut(?DateTime $dateDebut) : Campagne
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateFin() : ?DateTime
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime|null $dateFin
     * @return Campagne
     */
    public function setDateFin(?DateTime $dateFin) : Campagne
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    /**
     * @return Campagne|null
     */
    public function getPrecede() : ?Campagne
    {
        return $this->precede;
    }

    /**
     * @param Campagne|null $precede
     * @return Campagne
     */
    public function setPrecede(?Campagne $precede) : Campagne
    {
        $this->precede = $precede;
        return $this;
    }

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels()
    {
        return $this->entretiens->toArray();
    }


}