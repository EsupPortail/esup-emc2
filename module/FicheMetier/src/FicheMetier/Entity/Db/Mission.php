<?php

namespace FicheMetier\Entity\Db;

use Carriere\Entity\Db\NiveauEnveloppe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use FichePoste\Entity\Db\MissionAdditionnelle;
use Metier\Entity\HasDomainesInterface;
use Metier\Entity\HasDomainesTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Mission implements HistoriqueAwareInterface,
    HasDomainesInterface, HasApplicationCollectionInterface, HasCompetenceCollectionInterface
{
    use HistoriqueAwareTrait;
    use HasDomainesTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;

    private ?int $id = null;
    private ?string $libelle = null;

    /** Composition de la mission */
    private ?NiveauEnveloppe $niveau = null;
    private Collection $activites;

    /** Liste des éléments possèdant la mission */
    private Collection $listeFicheMetierMission;
    private Collection $listeFichePosteMission;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
        $this->activites = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();

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
}