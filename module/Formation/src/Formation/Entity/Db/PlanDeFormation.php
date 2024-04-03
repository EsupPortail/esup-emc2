<?php

namespace Formation\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class PlanDeFormation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?DateTime $dateDebut = null;
    private ?DateTime $dateFin = null;

    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDateDebut(): ?DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): ?DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /** @return Formation[] */
    public function getFormations(): array
    {
        return $this->formations->toArray();
    }

    /**
     * Bircole pour rendre compatible le formulaire de sélection des enseignements
     */
    public function getFormationListe(): array
    {
        return $this->formations->toArray();
    }

    /** MACROS ***************************************************************************************/

    public function getPeriode(): string
    {
        if ($this->getDateDebut() === null AND $this->getDateFin() === null) return "Aucune période précisée";
        $periode = "";
        if ($this->getDateDebut() !== null) $periode .= $this->getDateDebut()->format('d/m/Y'); else $periode .= "---";
        $periode .= " au ";
        if ($this->getDateFin() !== null) $periode .= $this->getDateFin()->format('d/m/Y'); else $periode .= "---";
        return $periode;
    }
}