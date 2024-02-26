<?php

namespace MissionSpecifique\Entity\Db;

use Application\Entity\Db\AgentMissionSpecifique;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class MissionSpecifique implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?MissionSpecifiqueTheme $theme = null;
    private ?MissionSpecifiqueType $type = null;
    private ?string $description = null;
    private Collection $affectations;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getTheme(): ?MissionSpecifiqueTheme
    {
        return $this->theme;
    }

    public function setTheme(?MissionSpecifiqueTheme $theme): void
    {
        $this->theme = $theme;
    }

    public function getType(): ?MissionSpecifiqueType
    {
        return $this->type;
    }

    public function setType(?MissionSpecifiqueType $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** @return AgentMissionSpecifique[] */
    public function getAffectations(): array
    {
        return $this->affectations->toArray();
    }

    /** MACROS ********************************************************************************************************/

    /** @noinspection PhpUnused */
    public function toStringLibelle(): string
    {
        if ($this->getLibelle() === null) {
            return "Aucun libellé fourni pour cette mission spécifique";
        }
        return $this->getLibelle();
    }

    /** @noinspection PhpUnused */
    public function toStringDescription(): string
    {
        if ($this->getDescription() === null) {
            return "Aucune description fournie pour cette mission spécifique";
        }
        return $this->getDescription();
    }

    /** @noinspection PhpUnused */
    public function toStringTheme(): string
    {
        if ($this->getTheme() === null || $this->getTheme()->getLibelle() === null) {
            return "Aucun thème fourni pour cette mission spécifique";
        }
        return $this->getTheme()->getLibelle();
    }

    /** @noinspection PhpUnused */
    public function toStringType(): string
    {
        if ($this->getType() === null || $this->getType()->getLibelle() === null) {
            return "Aucun type fourni pour cette mission spécifique";
        }
        return $this->getType()->getLibelle();
    }
}