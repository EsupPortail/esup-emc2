<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\HasAgentInterface;
use Autoform\Entity\Db\FormulaireInstance;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class EntretienProfessionnel implements HistoriqueAwareInterface, ResourceInterface, HasAgentInterface, HasEtatInterface {
    use HistoriqueAwareTrait;
    use HasEtatTrait;

    const ETAT_ACCEPTATION                  = 'ENTRETIEN_ACCEPTATION';
    const ETAT_ACCEPTER                     = 'ENTRETIEN_ACCEPTER';
    const ETAT_VALIDATION_RESPONSABLE       = 'ENTRETIEN_VALIDATION_RESPONSABLE';
    const ETAT_VALIDATION_OBSERVATION       = 'ENTRETIEN_VALIDATION_OBSERVATION';
    const ETAT_VALIDATION_HIERARCHIE        = 'ENTRETIEN_VALIDATION_HIERARCHIE';
    const ETAT_VALIDATION_AGENT             = 'ENTRETIEN_VALIDATION_AGENT';
    const DELAI_OBSERVATION                 = 8;


    public function generateTag() : string
    {
        return (implode('_', [$this->getResourceId(), $this->getId()]));
    }

    public function getResourceId() : string
    {
        return 'EntretienProfessionnel';
    }

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Agent */
    private $responsable;
    /** @var Campagne */
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
    /** @var ArrayCollection (Sursis) */
    private $sursis;

    /** @var ArrayCollection (ValidationInstance) */
    private $validations;

    /** @var string */
    private $token;
    /** @var DateTime */
    private $acceptation;

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return Agent|null
     */
    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent|null $agent
     * @return EntretienProfessionnel
     */
    public function setAgent(?Agent $agent) : EntretienProfessionnel
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getResponsable() : ?Agent
    {
        return $this->responsable;
    }

    /**
     * @param Agent|null $responsable
     * @return EntretienProfessionnel
     */
    public function setResponsable(?Agent $responsable) : EntretienProfessionnel
    {
        $this->responsable = $responsable;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnnee() : ?string
    {
        if ($this->campagne === null) return "Aucune campagne";
        return $this->campagne->getAnnee();
    }

    /**
     * @return Campagne
     */
    public function getCampagne()  : ?Campagne
    {
        return $this->campagne;
    }

    /**
     * @param Campagne|null $campagne
     * @return EntretienProfessionnel
     */
    public function setCampagne(?Campagne $campagne) : EntretienProfessionnel
    {
        $this->campagne = $campagne;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateEntretien() : ?DateTime
    {
        return $this->dateEntretien;
    }

    /**
     * @param DateTime|null $dateEntretien
     * @return EntretienProfessionnel
     */
    public function setDateEntretien(?DateTime $dateEntretien) : EntretienProfessionnel
    {
        $this->dateEntretien = $dateEntretien;
        return $this;
    }

    /**
     * @return string
     */
    public function getLieu() : ?string
    {
        return $this->lieu;
    }

    /**
     * @param string|null $lieu
     * @return EntretienProfessionnel
     */
    public function setLieu(?string $lieu) : EntretienProfessionnel
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return bool
     */
    public function isComplete() : bool
    {
        return ($this->getEtat() !== null AND $this->getEtat()->getCode() === self::ETAT_VALIDATION_HIERARCHIE);
    }

    /**
     * @return FormulaireInstance
     */
    public function getFormulaireInstance() : ?FormulaireInstance
    {
        return $this->formulaireInstance;
    }

    /**
     * @param FormulaireInstance|null $formulaireInstance
     * @return EntretienProfessionnel
     */
    public function setFormulaireInstance(?FormulaireInstance $formulaireInstance) : EntretienProfessionnel
    {
        $this->formulaireInstance = $formulaireInstance;
        return $this;
    }

    /**
     * @return FormulaireInstance
     */
    public function getFormationInstance() : FormulaireInstance
    {
        return $this->formationInstance;
    }

    /**
     * @param FormulaireInstance|null $formationInstance
     * @return EntretienProfessionnel
     */
    public function setFormationInstance(?FormulaireInstance $formationInstance) : EntretienProfessionnel
    {
        $this->formationInstance = $formationInstance;
        return $this;
    }

    /** OBSERVATIONS **************************************************************************************************/

    /**
     * @return Observation[]
     */
    public function getObservations() : array
    {
        return $this->observations->toArray();
    }

    /**
     * @param Observation|null $observation
     * @return boolean
     */
    public function hasObservation(?Observation $observation) : bool
    {
        return $this->observations->contains($observation);
    }

    /**
     * @param Observation|null $observation
     * @return EntretienProfessionnel
     */
    public function addObservation(?Observation $observation) : EntretienProfessionnel
    {
        if ($this->hasObservation($observation)) $this->observations->add($observation);
        return $this;
    }

    /**
     * @param Observation|null $observation
     * @return EntretienProfessionnel
     */
    public function removeObservation(?Observation $observation) : EntretienProfessionnel
    {
        $this->observations->removeElement($observation);
        return $this;
    }

    /**
     * @param Observation[] $observations
     * @return EntretienProfessionnel
     */
    public function setObservations(array $observations) : EntretienProfessionnel
    {
        $this->observations->clear();
        foreach ($observations as $observation) $this->addObservation($observation);
        return $this;
    }

    /**
     * @return Observation|null
     */
    public function getObservationActive() : ?Observation
    {
        $observation = null;
        /** @var Observation $obs */
        foreach ($this->observations as $obs) {
            if ($obs->estNonHistorise()) {
                if ($observation !== null) throw new RuntimeException("Plusieurs observations actives pour l'entretien #".$this->id, 0, null);
                $observation = $obs;
            }
        }
        return $observation;
    }

    /** SURSIS ********************************************************************************************************/

    /**
     * @return Sursis|null
     */
    public function getSursisActif() : ?Sursis
    {
        $sursis = null;
        /** @var Sursis $item */
        foreach ($this->sursis as $item) {
            if ($item->estNonHistorise()) {
                if ($sursis !== null) throw new RuntimeException("Plusieurs sursis actifs pour l'entretien #".$this->id, 0, null);
                $sursis = $item;
            }
        }
        return $sursis;
    }

    // Date d'entretien ou Date de sursis si il existe

    /**
     * @return DateTime
     */
    public function getMaxSaisiEntretien() : ?DateTime
    {
        $sursis = $this->getSursisActif();
        if ($sursis === null) {
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $this->getDateEntretien()->format('d/m/Y 23:59:59'));
        } else {
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $sursis->getSursis()->format('d/m/Y 23:59:59'));
        }
        return $date;

    }

    public function getMaxSaisiObservation() : ?DateTime
    {
        $validation = $this->getValidationByType(EntretienProfessionnelConstant::VALIDATION_RESPONSABLE);
        if ($validation === null) return null;

        $date = $validation->getHistoCreation();
        try {
            $date->add(new DateInterval('P' . EntretienProfessionnel::DELAI_OBSERVATION . 'D'));
        } catch (Exception $e) {
            throw new RuntimeException("Problème de création du DateInterval",0,$e);
        }
        return $date;
    }

    /** VALIDATION ****************************************************************************************************/
    // todo faire un trait pour généraliser les validations ???

    /**
     * @param string|null $type
     * @param bool $historisee
     * @return ValidationInstance[]
     */
    public function getValidations(?string $type = null, bool $historisee = false) : array
    {
        $validations =  $this->validations->toArray();
        if ($type !== null) $validations = array_filter($validations, function (ValidationInstance $a) use ($type) { return $a->getType()->getCode() === $type;});
        if ($historisee !== true) $validations = array_filter($validations, function (ValidationInstance $a) { return $a->estNonHistorise();});
        return $validations;
    }

    /**
     * @param ValidationInstance $validation
     * @return EntretienProfessionnel
     */
    public function addValidation(ValidationInstance $validation) : EntretienProfessionnel
    {
        $this->validations->add($validation);
        return $this;
    }

    /**
     * @param ValidationInstance $validation
     * @return EntretienProfessionnel
     */
    public function removeValidation(ValidationInstance $validation) : EntretienProfessionnel
    {
        $this->validations->removeElement($validation);
        return $this;
    }

    /**
     * @param string|null $type
     * @param bool $historisee
     * @return ValidationInstance|null
     */
    public function getValidationByType(?string $type, bool $historisee = false) : ?ValidationInstance
    {
        $validations =  $this->validations->toArray();
        if ($type !== null) $validations = array_filter($validations, function (ValidationInstance $a) use ($type) { return $a->getType()->getCode() === $type;});
        if ($historisee !== true) $validations = array_filter($validations, function (ValidationInstance $a) { return $a->getHistoDestruction() === null;});

        if ($validations === []) return null;
        usort($validations, function (ValidationInstance $a, ValidationInstance $b) { return $a->getHistoCreation() > $b->getHistoCreation();});
        return $validations[0];
    }

    /**
     * @return string
     */
    public function getToken() : ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return EntretienProfessionnel
     */
    public function setToken(?string $token) : EntretienProfessionnel
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getAcceptation() : ?DateTime
    {
        return $this->acceptation;
    }

    /**
     * @param DateTime|null $acceptation
     * @return EntretienProfessionnel
     */
    public function setAcceptation(?DateTime $acceptation) : EntretienProfessionnel
    {
        $this->acceptation = $acceptation;
        return $this;
    }

    /** MACROS ********************************************************************************************/

    public function toStringLieu() : string {
        if ($this->lieu !== null) return $this->lieu;
        return "Aucun lieu donné";
    }

    public function toStringDate() : string {
        if ($this->getDateEntretien() !== null) return $this->dateEntretien->format('d/m/Y à H:i');
        return "Aucune date donnée";
    }

    public function toStringAgent() : string {
        if ($this->getAgent() !== null) return $this->getAgent()->getDenomination();
        return "Aucun agent donné";
    }
    public function toStringResponsable() : string {
        if ($this->getResponsable() !== null) return $this->getResponsable()->getDenomination();
        return "Aucun responsable d'entretien donné";
    }

    public function  toStringValidationAgent() : string {
        if ($this->validationAgent !== null) {
            return $this->validationAgent->getHistoCreation()->format('d/m/Y à H:i'). " par " .$this->validationAgent->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation de l'agent";
    }

    public function  toStringValidationResponsable() : string {
        if ($this->validationResponsable !== null) {
            return $this->validationResponsable->getHistoCreation()->format('d/m/Y à H:i'). " par " .$this->validationResponsable->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation du responsable d'entretien";
    }

    public function  toStringValidationHierarchie() : string {
        if ($this->validationDRH !== null) {
            return $this->validationDRH->getHistoCreation()->format('d/m/Y à H:i'). " par " .$this->validationDRH->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation du responsable hiérarchique";
    }

    public function toStringCR_Entretien() : string {

        if ($this->formulaireInstance !== null) {
            return $this->formulaireInstance->prettyPrint();
        }
        return "Aucune formulaire";
    }

    public function toStringCR_Formation() : string {

        if ($this->formationInstance !== null) {
            return $this->formationInstance->prettyPrint();
        }
        return "Aucune formulaire";
    }
}