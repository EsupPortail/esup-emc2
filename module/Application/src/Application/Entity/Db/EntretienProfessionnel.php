<?php

namespace Application\Entity\Db;

use Autoform\Entity\Db\FormulaireInstance;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class EntretienProfessionnel implements HistoriqueAwareInterface, ResourceInterface {
    use HistoriqueAwareTrait;

    public function getResourceId()
    {
        return 'EntretienProfessionnel';
    }

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
    /** @var string */
    private $lieu;
    /** @var FormulaireInstance */
    private $formulaireInstance;
    /** @var FormulaireInstance */
    private $formationInstance;

    /** @var ArrayCollection (Observation) */
    private $observations;

    /** @var ValidationInstance */
    private $validationAgent;
    /** @var ValidationInstance */
    private $validationResponsable;
    /** @var ValidationInstance */
    private $validationDRH;
    /** @var string */
    private $token;
    /** @var DateTime */
    private $acceptation;


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
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param string $lieu
     * @return EntretienProfessionnel
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
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

    /**
     * @return FormulaireInstance
     */
    public function getFormationInstance()
    {
        return $this->formationInstance;
    }

    /**
     * @param FormulaireInstance $formationInstance
     * @return EntretienProfessionnel
     */
    public function setFormationInstance($formationInstance)
    {
        $this->formationInstance = $formationInstance;
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

    /**
     * @return EntretienProfessionnelObservation
     */
    public function getObservationActive()
    {
        $observation = null;
        /** @var EntretienProfessionnelObservation $obs */
        foreach ($this->observations as $obs) {
            if ($obs->estNonHistorise()) {
                if ($observation !== null) throw new RuntimeException("Plusieurs observations actives pour l'entretien #".$this->id, 0, null);
                $observation = $obs;
            }
        }
        return $observation;
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
     * @return bool
     */
    public function hasValidationAgent() {
        return ($this->validationAgent AND $this->validationAgent->estNonHistorise());
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
     * @return bool
     */
    public function hasValidationResponsable() {
        return ($this->validationResponsable AND $this->validationResponsable->estNonHistorise());
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

    /**
     * @return bool
     */
    public function hasValidationDRH() {
        return ($this->validationDRH AND $this->validationDRH->estNonHistorise());
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return EntretienProfessionnel
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getAcceptation()
    {
        return $this->acceptation;
    }

    /**
     * @param DateTime|null $acceptation
     * @return EntretienProfessionnel
     */
    public function setAcceptation($acceptation)
    {
        $this->acceptation = $acceptation;
        return $this;
    }
}