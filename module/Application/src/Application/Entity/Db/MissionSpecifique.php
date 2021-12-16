<?php

namespace Application\Entity\Db;

use Application\Entity\Db\MacroContent\MissionSpecifiqueMacroTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MissionSpecifique implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;
    use MissionSpecifiqueMacroTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var MissionSpecifiqueTheme */
    private $theme;
    /** @var MissionSpecifiqueType */
    private $type;
    /** @var string */
    private $description;
    /** @var ArrayCollection */
    private $affectations;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MissionSpecifique
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return MissionSpecifique
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return MissionSpecifiqueTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifique
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return MissionSpecifiqueType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifique
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return MissionSpecifique
     */
    public function setDescription(?string $description): MissionSpecifique
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return AgentMissionSpecifique[]
     */
    public function getAffectations() : array
    {
        return $this->affectations->toArray();
    }

}