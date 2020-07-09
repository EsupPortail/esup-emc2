<?php

namespace Application\Entity\Db;

use Autoform\Entity\Db\FormulaireInstance;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;

class EntretienProfessionnel implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var User */
    private $responsable;
    /** @var EntretienProfessionnelCampagne */
    private $campagne;
    /** @var DateTime */
    private $dateEntretien;
    /** @var FormulaireInstance */
    private $formulaireInstance;

    /** @var ArrayCollection (Observation) */
    private $observations;

    /** @var ValidationInstance */
    private $validationAgent;
    /** @var ValidationInstance */
    private $validationResponsable;
    /** @var ValidationInstance */
    private $validationDRH;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return EntretienProfessionnel
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
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
        if ($this->campagne === null) return "Aucune campagne";
        return $this->campagne->getAnnee();
    }

    /**
     * @return EntretienProfessionnelCampagne
     */
    public function getCampagne()
    {
        return $this->campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnel
     */
    public function setCampagne($campagne)
    {
        $this->campagne = $campagne;
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

    /** OBSERVATIONS **************************************************************************************************/

    /**
     * @return EntretienProfessionnelObservation[]
     */
    public function getObservations()
    {
        return $this->observations->toArray();
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return boolean
     */
    public function hasObservation($observation)
    {
        return $this->observations->contains($observation);
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnel
     */
    public function addObservation(EntretienProfessionnelObservation $observation)
    {
        if ($this->hasObservation($observation)) $this->observations->add($observation);
        return $this;
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnel
     */
    public function removeObservation(EntretienProfessionnelObservation $observation)
    {
        $this->observations->removeElement($observation);
        return $this;
    }

    /**
     * @param EntretienProfessionnelObservation[] $observations
     * @return EntretienProfessionnel
     */
    public function setObservations($observations)
    {
        $this->observations->clear();
        foreach ($observations as $observation) $this->addObservation($observation);
        return $this;
    }

    /** VALIDATION ****************************************************************************************************/

    /**
     * @return ValidationInstance
     */
    public function getValidationAgent()
    {
        return $this->validationAgent;
    }

    /**
     * @param ValidationInstance $validationAgent
     * @return EntretienProfessionnel
     */
    public function setValidationAgent($validationAgent)
    {
        $this->validationAgent = $validationAgent;
        return $this;
    }

    /**
     * @return ValidationInstance
     */
    public function getValidationResponsable()
    {
        return $this->validationResponsable;
    }

    /**
     * @param ValidationInstance $validationResponsable
     * @return EntretienProfessionnel
     */
    public function setValidationResponsable($validationResponsable)
    {
        $this->validationResponsable = $validationResponsable;
        return $this;
    }

    /**
     * @return ValidationInstance
     */
    public function getValidationDRH()
    {
        return $this->validationDRH;
    }

    /**
     * @param ValidationInstance $validationDRH
     */
    public function setValidationDRH($validationDRH)
    {
        $this->validationDRH = $validationDRH;
    }

}