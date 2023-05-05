<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\HasAgentInterface;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Validation\EntretienProfessionnelValidations;
use UnicaenAutoform\Entity\Db\FormulaireInstance;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class EntretienProfessionnel implements HistoriqueAwareInterface, ResourceInterface, HasAgentInterface, HasEtatInterface {
    use HistoriqueAwareTrait;
    use HasEtatTrait;

    const FORMULAIRE_CREP                   = 'CREP';
    const FORMULAIRE_CREF                   = 'CREF';

    const DELAI_OBSERVATION                 = 8;


    public function generateTag() : string
    {
        return (implode('_', [$this->getResourceId(), $this->getId()]));
    }

    public function getResourceId() : string
    {
        return 'EntretienProfessionnel';
    }

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?Agent $responsable = null;
    private ?Campagne $campagne = null;
    private ?DateTime $dateEntretien = null;
    private ?string $lieu = null;

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

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent) : void
    {
        $this->agent = $agent;
    }

    public function getResponsable() : ?Agent
    {
        return $this->responsable;
    }

    public function setResponsable(?Agent $responsable) : void
    {
        $this->responsable = $responsable;
    }

    public function getCampagne()  : ?Campagne
    {
        return $this->campagne;
    }

    public function setCampagne(?Campagne $campagne) : void
    {
        $this->campagne = $campagne;
    }

    public function getDateEntretien() : ?DateTime
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(?DateTime $dateEntretien) : void
    {
        $this->dateEntretien = $dateEntretien;
    }

    public function getLieu() : ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu) : void
    {
        $this->lieu = $lieu;
    }






    /**
     * @return bool
     */
    public function isComplete() : bool
    {
        return ($this->getEtat() !== null AND $this->getEtat()->getCode() === EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT);
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
        $validation = $this->getValidationByType(EntretienProfessionnelValidations::VALIDATION_RESPONSABLE);
        if ($validation === null) return null;

        $date = DateTime::createFromFormat("d/m/Y H:i:s", $validation->getHistoCreation()->format("d/m/Y H:i:s"));
        try {
            $tmp = 'P' . EntretienProfessionnel::DELAI_OBSERVATION . 'D';
            $date->add(new DateInterval($tmp));
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
        $validations = array_filter($validations, function (ValidationInstance $a) {return $a->estNonHistorise();});
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

    /** PREDICATS *****************************************************************************************************/

    /**
     * @param Agent|null $agent
     * @return bool
     */
    public function isAgent(?Agent $agent) : bool
    {
        return $agent === $this->getAgent();
    }

    /**
     * @param Agent|null $agent
     * @return bool
     */
    public function isReponsable(?Agent $agent) : bool
    {
        return $agent === $this->getResponsable();
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

    public function toStringReponsableNomUsage() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        if ($agent->getNomUsuel() === null) return "Aucun nom d'usage";
        return $agent->getNomUsuel();
    }

    public function toStringReponsableNomFamille() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        if ($agent->getNomFamille() === null) return "Aucun nom de famille";
        return $agent->getNomFamille();
    }

    public function toStringReponsablePrenom() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        if ($agent->getPrenom() === null) return "Aucun prénom";
        return $agent->getPrenom();
    }

    public function toStringReponsableCorpsGrade() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        $grades = ($agent->getGradesActifs()) ? $agent->getGradesActifs() : null;

        if ($grades === null) return "Aucune date";

        $texte = "";
        foreach ($grades as $grade) {
            $texte .= $grade->getCorps()->getLibelleLong() . "  - " . $grade->getGrade()->getLibelleLong();
        }
        return $texte;
    }

    public function toStringReponsableStructure() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        $structure = ($agent->getAffectationPrincipale())?$agent->getAffectationPrincipale()->getStructure():null;

        if ($structure === null) return "Aucune Structure";
        if ($structure->getNiv2() === null or $structure === $structure->getNiv2()) return $structure->getLibelleLong();

        return $structure->getNiv2()->getLibelleLong();
    }

    public function toStringReponsableIntitulePoste() : string
    {
        /** @var Agent $responsble */
        $responsble = $this->getResponsable();
        $fiche = $responsble->getFichePosteBest();

        if ($fiche === null) return "Aucune fiche de poste EMC2";
        $metier  = $fiche->getLibelleMetierPrincipal();
        $complement = $fiche->getLibelle();
        if ($metier === null) return "Aucun métier principal dans la fiche [".$complement."]";

        if ($complement) return $metier . " rattaché à " . $complement;
        return $metier;
    }

    public function  toStringValidationAgent() : string {
        $validation = $this->getValidationByType(EntretienProfessionnelValidations::VALIDATION_AGENT);
        if ($validation !== null) {
            return $validation->getHistoCreation()->format('d/m/Y à H:i'). " par " .$validation->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation de l'agent";
    }

    public function  toStringValidationResponsable() : string {
        $validation = $this->getValidationByType(EntretienProfessionnelValidations::VALIDATION_RESPONSABLE);
        if ($validation !== null) {
            return $validation->getHistoCreation()->format('d/m/Y à H:i'). " par " .$validation->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation du responsable d'entretien";
    }

    public function  toStringValidationHierarchie() : string {
        $validation = $this->getValidationByType(EntretienProfessionnelValidations::VALIDATION_DRH);
        if ($validation !== null) {
            return $validation->getHistoCreation()->format('d/m/Y à H:i'). " par " .$validation->getHistoCreateur()->getDisplayName();
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

    public function toStringObservationEntretien() : string {
        $observation = $this->getObservationActive();
        if ($observation AND $observation->getObservationAgentEntretien()) return $observation->getObservationAgentEntretien();
        return "Aucune observation";
    }

    public function toStringObservationPerspective() : string {
        $observation = $this->getObservationActive();
        if ($observation AND $observation->getObservationAgentPerspective()) return $observation->getObservationAgentPerspective();
        return "Aucune observation";
    }
    public function toString_CREP_projet() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'projet']);
        if ($res === null OR trim($res) === '' ) return "Non";
        return "Oui";
    }
    public function toString_CREP_encadrement() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement']);
        if ($res === null OR trim($res) === '' ) return "Non";
        return "Oui";
    }

    public function toString_CREP_encadrementA() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement_A']);
        if ($res === null OR trim($res) === '' ) return "0";
        return $res;
    }
    public function toString_CREP_encadrementB() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement_B']);
        if ($res === null OR trim($res) === '' ) return "0";
        return $res;
    }
    public function toString_CREP_encadrementC() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement_C']);
        if ($res === null OR trim($res) === '' ) return "0";
        return $res;
    }

    public function toString_CREP_experiencepro() : string {
        $texte1 =  $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'exppro_1']);
        $texte2 =  $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'exppro_2']);

        $res = $texte1;
        if ($texte2 AND trim($texte2) !== '') {
            $res = "<p>Autres : " . $texte2 . "</p>";
        }
        return $res;
    }

    public function toStringCREP_Champ($motsClefs)
    {
        $mots = explode(";", $motsClefs);
        $texte = $this->formulaireInstance->fetchChampReponseByMotsClefs($mots);
        return str_replace("_"," ",$texte);
    }

    /** MACRO du CREF *************************************************************************************************/

    public function toStringCREF_Champ($motsClefs) : string
    {
        $mots = explode(";", $motsClefs);
        return $this->formationInstance->fetchChampReponseByMotsClefs($mots);
    }

    public function toStringCREF_Champs($motsClefs) : string
    {
        $mots = explode(";", $motsClefs);
        return $this->formationInstance->fetchChampsReponseByMotsClefs($mots);
    }
}