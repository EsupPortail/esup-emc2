<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\MacroContent\StructureMacroTrait;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Application\Entity\SynchroAwareInterface;
use Application\Entity\SynchroAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Structure implements ResourceInterface, SynchroAwareInterface, HasDescriptionInterface {
    use DbImportableAwareTrait;
    use HasDescriptionTrait;
    use SynchroAwareTrait;
    use StructureMacroTrait;

    public function getResourceId()
    {
        return 'Structure';
    }

    /** @var string */
    private $id;
    /** @var integer */
    private $source_id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var StructureType */
    private $type;
    /** @var DateTime */
    private $ouverture;
    /** @var DateTime */
    private $fermeture;
    /** @var Boolean */
    private $repriseResumeMere;
    /** @var Structure */
    private $parent;
    /** @var ArrayCollection (Structure) */
    private $enfants;

    /** @var ArrayCollection */
    private $gestionnaires;
    /** @var ArrayCollection */
    private $responsables;
    /** @var ArrayCollection (Poste) */
    private $postes;
    /** @var ArrayCollection (AgentMissionSpecifique) */
    private $missions;

    /** @var ArrayCollection (StructureAgentForce) */
    private $agentsForces;
    /** @var ArrayCollection (FichePoste) */
    private $fichesPostesRecrutements;

    public function __construct()
    {
        $this->gestionnaires = new ArrayCollection();
        $this->responsables = new ArrayCollection();
        $this->postes = new ArrayCollection();
        $this->enfants = new ArrayCollection();
        $this->agentsForces = new ArrayCollection();
        $this->fichesPostesRecrutements = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLibelleCourt() : string
    {
        return $this->libelleCourt;
    }

    /**
     * @return string
     */
    public function getLibelleLong() : string
    {
        return $this->libelleLong;
    }

    /**
     * @return StructureType
     */
    public function getType() : StructureType
    {
        return $this->type;
    }

    /**
     * @return DateTime|null
     */
    public function getOuverture() : ?DateTime
    {
        return $this->ouverture;
    }

    /**
     * @return DateTime|null
     */
    public function getFermeture() : ?DateTime
    {
        return $this->fermeture;
    }

    /**
     * @return string
     */
    public function getDescriptionComplete() : string
    {
        $text = "";
        if ($this->getRepriseResumeMere() AND $this->parent !== null) {
            $text .= $this->parent->getDescription() . "<br/>";
        }
        return $text . $this->description ;
    }

    /** GESTIONNAIRES ET RESPONSABLES **************************************************************************/

    /**
     * @return User[]
     */
    public function getGestionnaires() : array
    {
        if ($this->gestionnaires === null) return [];
        return $this->gestionnaires->toArray();
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function addGestionnaire(User $user) : Structure
    {
        $this->gestionnaires->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function removeGestionnaire(User $user) : Structure
    {
        $this->gestionnaires->removeElement($user);
        return $this;
    }

    /**
     * @return User[]
     */
    public function getResponsables() : array
    {
        if ($this->responsables === null) return [];
        return $this->responsables->toArray();
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function addResponsable(User $user) : Structure
    {
        $this->responsables->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function removeResponsable(User $user) : Structure
    {
        $this->responsables->removeElement($user);
        return $this;
    }

    /** POSTE ****************************************************************************************************/

    /**
     * @return Poste[]
     */
    public function getPostes() : array
    {
        return $this->postes->toArray();
    }

    /**
     * @param Poste $poste
     * @return Structure
     */
    public function addPoste(Poste $poste) : Structure
    {
        $this->postes->add($poste);
        return $this;
    }

    /**
     * @param Poste $poste
     * @return Structure
     */
    public function removePoste(Poste $poste) : Structure
    {
        $this->postes->removeElement($poste);
        return $this;
    }

    /**
     * @param Poste $poste
     * @return boolean
     */
    public function hasPoste(Poste $poste) : bool
    {
        return $this->postes->contains($poste);
    }
    /**
     * @return AgentMissionSpecifique[]
     */
    public function getMissions() : array
    {
        return $this->missions->toArray();
    }

    /**
     * @param AgentMissionSpecifique $mission
     * @return Structure
     */
    public function addMission(AgentMissionSpecifique $mission) : Structure
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param AgentMissionSpecifique $mission
     * @return Structure
     */
    public function removeMission(AgentMissionSpecifique $mission) : Structure
    {
        $this->postes->removeElement($mission);
        return $this;
    }

    /**
     * @param AgentMissionSpecifique $mission
     * @return boolean
     */
    public function hasMission(AgentMissionSpecifique $mission) : bool
    {
        return $this->missions->contains($mission);
    }

    /**
     * @return Structure|null
     */
    public function getParent() : ?Structure
    {
        return $this->parent;
    }

    /**
     * @return Structure[]
     */
    public function getEnfants() : array
    {
        return $this->enfants->toArray();
    }

    /**
     * @return bool
     */
    public function getRepriseResumeMere() : bool
    {
        return $this->repriseResumeMere;
    }

    /**
     * @param bool $repriseResumeMere
     * @return Structure
     */
    public function setRepriseResumeMere(bool $repriseResumeMere) : Structure
    {
        $this->repriseResumeMere = $repriseResumeMere;
        return $this;
    }

    /** AGENTS FORCES *************************************************************************************************/

    /**
     * @var bool $keepHisto
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

    /**
     * @var FichePoste|null $fiche
     * @return Structure
     */
    public function addFichePosteRecrutement(?FichePoste $fiche) : Structure
    {
        $this->fichesPostesRecrutements->add($fiche);
        return $this;
    }

    /**
     * @var FichePoste|null $fiche
     * @return Structure
     */
    public function removeFichePosteRecrutement(?FichePoste $fiche) : Structure
    {
        $this->fichesPostesRecrutements->removeElement($fiche);
        return $this;
    }


    /**
     * @return string
     */
    public function __toString() : string
    {
        $text = "[".$this->getType()."] ";
        $text .= $this->getLibelleCourt();
        return $text;
    }
}