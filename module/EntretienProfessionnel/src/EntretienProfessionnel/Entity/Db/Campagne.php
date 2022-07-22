<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Campagne implements HasPeriodeInterface, HistoriqueAwareInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $annee;
    /** @var Campagne */
    private $precede;
    /** @var DateTime|null */
    private $dateCirculaire;

    /** @var ArrayCollection (EntretienProfessionnel) */
    private $entretiens;


    /**
     * @return string
     */
    public function generateTag() : string
    {
        return 'Campagne_' . $this->getId();
    }

    /**
     * @return int
     */
    public function getId() : ?int
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
     * @return DateTime|null
     */
    public function getDateCirculaire(): ?DateTime
    {
        return $this->dateCirculaire;
    }

    /**
     * @param DateTime|null $dateCirculaire
     * @return Campagne
     */
    public function setDateCirculaire(?DateTime $dateCirculaire): Campagne
    {
        $this->dateCirculaire = $dateCirculaire;
        return $this;
    }

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels() : array
    {
        return $this->entretiens->toArray();
    }

    /** Fonctions pour les macros *************************************************************************************/

    public function getDateCirculaireToString() : string
    {
        return ($this->dateCirculaire)?$this->dateCirculaire->format('d/m/Y'):"N.C.";
    }
}