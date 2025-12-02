<?php

namespace FicheMetier\Entity\Db;

use Carriere\Entity\Db\NiveauEnveloppe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FichePoste\Entity\Db\MissionAdditionnelle;
use Metier\Entity\Db\Interface\HasFamillesProfessionnellesInterface;
use Metier\Entity\Db\Trait\HasFamillesProfessionnellesTrait;
use Referentiel\Entity\Db\Interfaces\HasReferenceInterface;
use Referentiel\Entity\Db\Traits\HasReferenceTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Mission implements HistoriqueAwareInterface,
    HasFamillesProfessionnellesInterface, HasReferenceInterface
{
    use HistoriqueAwareTrait;
    use HasFamillesProfessionnellesTrait;
    use HasReferenceTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $codesFicheMetier = null;

    /** Composition de la mission */
    private ?NiveauEnveloppe $niveau = null;
    private Collection $activites;

    /** Liste des éléments possèdant la mission */
    private Collection $listeFicheMetierMission;
    private Collection $listeFichePosteMission;

    /** Source de la mission principale */
    private ?string $sourceString = null;

    public function __construct()
    {
        $this->famillesProfessionnelles = new ArrayCollection();
        $this->activites = new ArrayCollection();

        $this->listeFicheMetierMission = new ArrayCollection();
        $this->listeFichePosteMission = new ArrayCollection();
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

    public function getNiveau(): ?NiveauEnveloppe
    {
        return $this->niveau;
    }

    public function setNiveau(?NiveauEnveloppe $niveau): void
    {
        $this->niveau = $niveau;
    }

    /**
     * @return MissionActivite[]
     */
    public function getActivites(): array
    {
        return $this->activites->toArray();
    }

    /** Liste des éléments possèdant la mission */

    /** @return FicheMetierMission[] */
    public function getListeFicheMetier(): array
    {
        $result = [];
        foreach ($this->listeFicheMetierMission as $ficheMetierMission) {
            $result[] = $ficheMetierMission;
        }
        return $result;
    }

    /** @return MissionAdditionnelle[] */
    public function getListeFichePoste(bool $historise = false): array
    {
        $result = [];
        /** @var MissionAdditionnelle $fichePosteMission */
        foreach ($this->listeFichePosteMission as $fichePosteMission) {
            if (!$historise or $fichePosteMission->estNonHistorise()) {
                $result[] = $fichePosteMission;
            }
        }
        return $result;
    }

    public function addMissionActivite(MissionActivite $activite): void
    {
        $this->activites->add($activite);
    }

    public function clearActivites(): void
    {
        $this->activites->clear();
    }

    public function getCodesFicheMetier(): ?string
    {
        return $this->codesFicheMetier;
    }

    public function setCodesFicheMetier(?string $codesFicheMetier): ?string
    {
        return $this->codesFicheMetier = $codesFicheMetier;
    }

    public function getSourceString(): ?string
    {
        return $this->sourceString;
    }

    public function setSourceString(?string $sourceString): void
    {
        $this->sourceString = $sourceString;
    }


}