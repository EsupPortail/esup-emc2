<?php

namespace Application\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class EntretienProfessionnelCampagne implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $annee;
    /** @var DateTime */
    private $dateDebut;
    /** @var DateTime */
    private $dateFin;
    /** @var EntretienProfessionnelCampagne */
    private $precede;
    /** @var ArrayCollection (EntretienProfessionnel) */
    private $entretiens;

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
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * @param string $annee
     * @return EntretienProfessionnelCampagne
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime $dateDebut
     * @return EntretienProfessionnelCampagne
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime $dateFin
     * @return EntretienProfessionnelCampagne
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    /**
     * @return EntretienProfessionnelCampagne
     */
    public function getPrecede()
    {
        return $this->precede;
    }

    /**
     * @param EntretienProfessionnelCampagne $precede
     * @return EntretienProfessionnelCampagne
     */
    public function setPrecede($precede)
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