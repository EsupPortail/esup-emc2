<?php

namespace FicheMetier\Entity\Db;

use Carriere\Entity\Db\Interface\HasFamillesProfessionnellesInterface;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Entity\Db\Trait\HasFamillesProfessionnellesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FichePoste\Entity\Db\MissionAdditionnelle;
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

    const MISSION_PRINCIPALE_HEADER_ID = 'Id_Mission';
    const MISSION_PRINCIPALE_HEADER_LIBELLE = 'Libellé';
    const MISSION_PRINCIPALE_HEADER_FAMILLES = 'Familles professionnelles';
    const MISSION_PRINCIPALE_HEADER_NIVEAU = 'Niveau';
    const MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE = 'Codes Emploi Type';
    const MISSION_PRINCIPALE_HEADER_CODES_FONCTION = 'Codes Fonction';

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $codesFicheMetier = null;
    private ?string $codesFonction = null;
    private ?NiveauEnveloppe $niveau = null;

    /** Liste des éléments possèdant la mission */
    private Collection $listeFicheMetierMission;
    private Collection $listeFichePosteMission;

    /** Source de la mission principale */
    private ?string $sourceString = null;

    public function __construct()
    {
        $this->famillesProfessionnelles = new ArrayCollection();

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

    public function removeFicheMetier(FicheMetierMission $ficheMetier): void
    {
        $this->listeFicheMetierMission->removeElement($ficheMetier);
    }

    public function clearListeFicheMetier(): void
    {
        $this->listeFicheMetierMission->clear();
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

    public function getCodesFicheMetier(): ?string
    {
        return $this->codesFicheMetier;
    }

    public function setCodesFicheMetier(?string $codesFicheMetier): ?string
    {
        return $this->codesFicheMetier = $codesFicheMetier;
    }

    public function getCodesFonction(): ?string
    {
        return $this->codesFonction;
    }

    public function setCodesFonction(?string $codesFonction): ?string
    {
        return $this->codesFonction = $codesFonction;
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