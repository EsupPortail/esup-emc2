<?php

namespace Application\Entity\Db;

use Autoform\Entity\Db\FormulaireInstance;
use DateTime;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class EntretienProfessionnel {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var User */
    private $personnel;
    /** @var User */
    private $responsable;
    /** @var string */
    private $annee;
    /** @var DateTime */
    private $dateEntretien;
    /** @var FormulaireInstance */
    private $formulaireInstance;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getPersonnel()
    {
        return $this->personnel;
    }

    /**
     * @param User $personnel
     * @return EntretienProfessionnel
     */
    public function setPersonnel($personnel)
    {
        $this->personnel = $personnel;
        return $this;
    }

    /**
     * @return User
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param User $responsable
     * @return EntretienProfessionnel
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
        return $this;
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
     * @return EntretienProfessionnel
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateEntretien()
    {
        return $this->dateEntretien;
    }

    /**
     * @param DateTime $dateEntretien
     * @return EntretienProfessionnel
     */
    public function setDateEntretien($dateEntretien)
    {
        $this->dateEntretien = $dateEntretien;
        return $this;
    }

    /**
     * @return FormulaireInstance
     */
    public function getFormulaireInstance()
    {
        return $this->formulaireInstance;
    }

    /**
     * @param FormulaireInstance $formulaireInstance
     * @return EntretienProfessionnel
     */
    public function setFormulaireInstance($formulaireInstance)
    {
        $this->formulaireInstance = $formulaireInstance;
        return $this;
    }


}