<?php

namespace Application\Entity\Db;

use Application\Entity\SynchroAwareInterface;
use Application\Entity\SynchroAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Structure implements ResourceInterface, SynchroAwareInterface {
    use ImportableAwareTrait;
    use SynchroAwareTrait;

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

    /** @var string */
    private $description;
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

    public function __construct()
    {
        $this->gestionnaires = new ArrayCollection();
        $this->responsables = new ArrayCollection();
        $this->postes = new ArrayCollection();
        $this->enfants = new ArrayCollection();
        $this->agentsForces = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * @return StructureType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getOuverture()
    {
        return $this->ouverture;
    }

    /**
     * @return DateTime
     */
    public function getFermeture()
    {
        return $this->fermeture;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $text = "";
        if ($this->getRepriseResumeMere() AND $this->parent !== null) {
            $text .= $this->parent->getDescription() . "<br/>";
        }
        return $text . $this->description ;
    }

    /**
     * @param string $description
     * @return Structure
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /** GESTIONNAIRES ET RESPONSABLES **************************************************************************/

    /**
     * @return User[]
     */
    public function getGestionnaires()
    {
        if ($this->gestionnaires === null) return [];
        return $this->gestionnaires->toArray();
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function addGestionnaire(User $user)
    {
        $this->gestionnaires->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function removeGestionnaire(User $user)
    {
        $this->gestionnaires->removeElement($user);
        return $this;
    }

    /**
     * @return User[]
     */
    public function getResponsables()
    {
        if ($this->responsables === null) return [];
        return $this->responsables->toArray();
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function addResponsable(User $user)
    {
        $this->responsables->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function removeResponsable(User $user)
    {
        $this->responsables->removeElement($user);
        return $this;
    }

    /** POSTE ****************************************************************************************************/

    /**
     * @return Poste[]
     */
    public function getPostes()
    {
        return $this->postes->toArray();
    }

    /**
     * @param Poste $poste
     * @return Structure
     */
    public function addPoste($poste)
    {
        $this->postes->add($poste);
        return $this;
    }

    /**
     * @param Poste $poste
     * @return Structure
     */
    public function removePoste($poste)
    {
        $this->postes->removeElement($poste);
        return $this;
    }

    /**
     * @param Poste $poste
     * @return boolean
     */
    public function hasPoste($poste)
    {
        return $this->postes->contains($poste);
    }
    /**
     * @return AgentMissionSpecifique[]
     */
    public function getMissions()
    {
        return $this->missions->toArray();
    }

    /**
     * @param AgentMissionSpecifique $mission
     * @return Structure
     */
    public function addMission($mission)
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param AgentMissionSpecifique $mission
     * @return Structure
     */
    public function removeMission($mission)
    {
        $this->postes->removeElement($mission);
        return $this;
    }

    /**
     * @param AgentMissionSpecifique $mission
     * @return boolean
     */
    public function hasMission($mission)
    {
        return $this->missions->contains($mission);
    }

    /**
     * @return Structure
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Structure $parent
     * @return Structure
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEnfants()
    {
        return $this->enfants;
    }

    /**
     * @param ArrayCollection $enfants
     * @return Structure
     */
    public function setEnfants($enfants)
    {
        $this->enfants = $enfants;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRepriseResumeMere()
    {
        return $this->repriseResumeMere;
    }

    /**
     * @param bool $repriseResumeMere
     * @return Structure
     */
    public function setRepriseResumeMere($repriseResumeMere)
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

    /**
     * @return string
     */
    public function __toString()
    {
        $text =  "";
        $text .= "[".$this->getType()."] ";
        $text .= $this->getLibelleCourt();
        return $text;
    }
}