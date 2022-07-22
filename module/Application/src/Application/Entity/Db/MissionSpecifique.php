<?php

namespace Application\Entity\Db;

use Application\Entity\Db\MacroContent\MissionSpecifiqueMacroTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

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
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return MissionSpecifique
     */
    public function setLibelle(?string $libelle) : MissionSpecifique
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return MissionSpecifiqueTheme
     */
    public function getTheme() : ?MissionSpecifiqueTheme
    {
        return $this->theme;
    }

    /**
     * @param MissionSpecifiqueTheme|null $theme
     * @return MissionSpecifique
     */
    public function setTheme(?MissionSpecifiqueTheme $theme) : MissionSpecifique
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return MissionSpecifiqueType
     */
    public function getType() : ?MissionSpecifiqueType
    {
        return $this->type;
    }

    /**
     * @param MissionSpecifiqueType|null $type
     * @return MissionSpecifique
     */
    public function setType(?MissionSpecifiqueType $type) : MissionSpecifique
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