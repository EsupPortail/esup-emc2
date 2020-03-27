<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Structure implements ResourceInterface{
    use ImportableAwareTrait;

    public function getResourceId()
    {
        return 'structure';
    }

    /** @var string */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $type;
    /** @var string */
    private $histo;
    /** @var Structure */
    private $parent;
    /** @var ArrayCollection (Structure) */
    private $enfants;

    /** @var string */
    private $description;
    /** @var ArrayCollection */
    private $gestionnaires;
    /** @var ArrayCollection (Poste) */
    private $postes;
    /** @var ArrayCollection (AgentMissionSpecifique) */
    private $missions;

    public function __construct()
    {
        $this->gestionnaires = new ArrayCollection();
        $this->postes = new ArrayCollection();
        $this->enfants = new ArrayCollection();
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
     * @param string $code
     * @return Structure
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @param string $libelleCourt
     * @return Structure
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * @param string $libelleLong
     * @return Structure
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Structure
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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

    /**
     * @return User[]
     */
    public function getGestionnaires()
    {
        return $this->gestionnaires->toArray();
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function addGestionnaire($user)
    {
        $this->gestionnaires->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function removeGestionnaire($user)
    {
        $this->gestionnaires->removeElement($user);
        return $this;
    }

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
     * @return string
     */
    public function getHisto()
    {
        return $this->histo;
    }

    /**
     * @param string $histo
     * @return Structure
     */
    public function setHisto($histo)
    {
        $this->histo = $histo;
        return $this;
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