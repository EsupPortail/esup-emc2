<?php

namespace Structure\Entity\Db;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Poste;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class Structure implements ResourceInterface, HasDescriptionInterface {
    use DbImportableAwareTrait;
    use HasDescriptionTrait;

    public function getResourceId() : string
    {
        return 'Structure';
    }

    /**
     * @return string
     */
    public function generateTag()  : string
    {
        return 'Structure_' . $this->getId();
    }

    private ?string $id = null;

    private ?int $source_id = -1;
    private ?string $code = null;

    private ?StructureType $type = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;

    private ?DateTime $ouverture = null;
    private ?DateTime $fermeture = null;
    private ?DateTime $fermetureOW = null;

    private ?string $adresseFonctionnelle = null;
    private bool $repriseResumeMere = false;

    private ?Structure $parent;
    private ?Structure $niv2;
    private ?Structure $niv2OverWriten;
    private Collection $enfants;            // [Structure]

    private Collection $gestionnaires;      //[StructureGestionnaire]
    private Collection $responsables;       //[StructureResponsable]

    private Collection $postes;             //[Poste]
    private Collection $missions;           //[AgentMissionSpecifique]

    private Collection $affectations;       //[AgentAffectation]
    private Collection $agentsForces;       //[StructureAgentForce]

    private Collection $fichesPostesRecrutements; //[FichePoste]

    public function getId() : string
    {
        return $this->id;
    }

    public function getCode() : string
    {
        return $this->code;
    }

    public function getLibelleCourt() : string
    {
        return $this->libelleCourt;
    }

    public function getLibelleLong() : string
    {
        return $this->libelleLong;
    }

    public function getType() : StructureType
    {
        return $this->type;
    }

    public function getOuverture() : ?DateTime
    {
        return $this->ouverture;
    }

    public function getFermeture() : ?DateTime
    {
        if ($this->getFermetureOW() !== null) return $this->getFermetureOW();
        return $this->fermeture;
    }

    public function getFermetureOW() : ?DateTime
    {
        return $this->fermetureOW;
    }

    public function isOuverte(?DateTime $date = null): bool
    {
        if ($date === null) $date = new DateTime();
        if ($this->ouverture > $date) return false;
        if ($this->getFermeture() !== null AND $this->getFermeture() <= $date) return false;
        return true;
    }

    public function getDescriptionComplete() : string
    {
        $text = "";
        if ($this->getRepriseResumeMere() AND $this->parent !== null) {
            $text .= $this->parent->getDescriptionComplete() . "<br/>";
        }
        return $text . $this->description ;
    }

    public function getAdresseFonctionnelle(): ?string
    {
        return $this->adresseFonctionnelle;
    }

//    public function setAdresseFonctionnelle(?string $adresseFonctionnelle): Structure
//    {
//        $this->adresseFonctionnelle = $adresseFonctionnelle;
//        return $this;
//    }

    /** GESTIONNAIRES ET RESPONSABLES **************************************************************************/

    /**
     * @return StructureGestionnaire[]
     */
    public function getGestionnaires() : array
    {
        //if ($this->gestionnaires === null) return [];
        $array = $this->gestionnaires->toArray();
        $array = array_filter($array, function (StructureGestionnaire $a) { return !$a->isDeleted();});
        return $array;
    }

    /**
     * @return StructureResponsable[]
     */
    public function getResponsables() : array
    {
        //if ($this->responsables === null) return [];
        $array = $this->responsables->toArray();
        $array = array_filter($array, function (StructureResponsable $a) { return !$a->isDeleted() ;});
        return $array;
    }

    /** POSTE - STATUER ****************************************************************************************************/

    public function getPostes() : array
    {
        return $this->postes->toArray();
    }

    public function addPoste(Poste $poste) : void
    {
        $this->postes->add($poste);
    }

    public function removePoste(Poste $poste) : void
    {
        $this->postes->removeElement($poste);
    }

    public function hasPoste(Poste $poste) : bool
    {
        return $this->postes->contains($poste);
    }

    public function getMissions() : array
    {
        return $this->missions->toArray();
    }

    public function addMission(AgentMissionSpecifique $mission) : void
    {
        $this->missions->add($mission);
    }

    public function removeMission(AgentMissionSpecifique $mission) : void
    {
        $this->postes->removeElement($mission);
    }

    public function hasMission(AgentMissionSpecifique $mission) : bool
    {
        return $this->missions->contains($mission);
    }

    public function getParent() : ?Structure
    {
        return $this->parent;
    }

    public function getNiv2() : ?Structure
    {
        if ($this->getNiv2OW() !== null) return $this->getNiv2OW();
        return $this->niv2;
    }

    public function getNiv2OW() : ?Structure
    {
        return $this->niv2OverWriten;
    }

    /**
     * @return Structure[]
     */
    public function getEnfants() : array
    {
        $enfants = $this->enfants->toArray();
        $enfants = array_filter($enfants, function(Structure $a) { return $a->isOuverte(); });
        return $enfants;
    }

    public function getRepriseResumeMere() : bool
    {
        return $this->repriseResumeMere;
    }

    public function setRepriseResumeMere(bool $repriseResumeMere) : void
    {
        $this->repriseResumeMere = $repriseResumeMere;
    }

    /** AGENTS FORCES *************************************************************************************************/

    /**
     * @return StructureAgentForce[]
     */
    public function getAgentsForces(bool $keepHisto = false) : array
    {
        $result = $this->agentsForces->toArray();
        if (! $keepHisto) {
            $result = array_filter($result, function (StructureAgentForce $a) { return $a->estNonHistorise();});
        }

        return $result;
    }

    /** FICHES POSTES RECRUTEMENTS ************************************************************************************/

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesRecrutements() : array
    {
        return $this->fichesPostesRecrutements->toArray();
    }

    public function addFichePosteRecrutement(?FichePoste $fiche) : void
    {
        $this->fichesPostesRecrutements->add($fiche);
    }

    public function removeFichePosteRecrutement(?FichePoste $fiche) : void
    {
        $this->fichesPostesRecrutements->removeElement($fiche);
    }


    public function __toString() : string
    {
        $text = "[".$this->getType()."] ";
        $text .= $this->getLibelleCourt();
        return $text;
    }

    /** MACRO *********************************************************************************************************/

    /**  @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction) */
    public function toStringLibelle() : string
    {
        $texte = $this->getLibelleLong();
        return $texte;
    }

    /**  @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction) */
    public function toStringLibelleLong() : string
    {
        $texte = "";

        $niv2 = $this->getNiv2();
        if ($niv2 !== null AND $niv2 !== $this) $texte .= $niv2->getLibelleLong() . " > ";
        $texte .= $this->getLibelleLong();

        return $texte;
    }

    /**  @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction) */
    public function toStringResume() : string
    {
        /** @var Structure $structure */
        $structure = $this;
        $texte = "";
        if ($structure->getRepriseResumeMere()) {
            $texte .= $structure->getParent()->toStringResume();
        }
        if ($structure->getDescription() !== null AND trim($structure->getDescription() !== '')) {
            $texte .= "<h3>" . $structure->toStringLibelle() . "</h3>";
            $texte .= $structure->getDescription();
        }
        return $texte;
    }

    /**  @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction) */
    public function toStringStructureBloc() : string
    {
        /** @var Structure $structure */
        $structure = $this;
        $texte = "";
        if ($structure->getRepriseResumeMere()) {
            $texte .= $structure->getParent()->toStringStructureBloc();
        }
        $texte .= "<h3>" . $structure->toStringLibelle() . "</h3>";
        $texte .= $structure->getDescription();
        return $texte;
    }

    /**  @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction) */
    public function toStringResponsables() : string
    {
        $date = new DateTime();
        $responsables = $this->getResponsables();
        $responsables = array_filter($responsables, function (StructureResponsable $a) use ($date) {
            $encours = $a->estEnCours($date);
            $effacer = $a->isDeleted($date);
            return ($encours AND !$effacer);
        });

        if (empty($responsables)) return "aucun·e responsable";
        $texte  = "<ul>";
        foreach ($responsables as $responsable) $texte .= "<li>".$responsable->getAgent()->getDenomination()."</li>";
        $texte .= "</ul>";
        return $texte;
    }

    /**  @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction) */
    public function toStringGestionnaires() : string
    {
        $date = new DateTime();
        $gestionnaires = $this->getGestionnaires();
        $gestionnaires = array_filter($gestionnaires, function (StructureGestionnaire $a) use ($date) {
            $encours = $a->estEnCours($date);
            $effacer = $a->isDeleted($date);
            return ($encours AND !$effacer);
        });

        if (empty($gestionnaires)) return "aucun·e gestionnaire";
        $texte  = "<ul>";
        foreach ($gestionnaires as $gestionnaire) $texte .= "<li>".$gestionnaire->getAgent()->getDenomination()."</li>";
        $texte .= "</ul>";
        return $texte;
    }
}