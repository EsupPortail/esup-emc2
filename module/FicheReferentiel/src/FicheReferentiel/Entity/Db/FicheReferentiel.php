<?php

namespace FicheReferentiel\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Metier\Entity\Db\Metier;
use Metier\Entity\Db\Referentiel;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheReferentiel implements HistoriqueAwareInterface, HasCompetenceCollectionInterface {
    use HistoriqueAwareTrait;
    use HasCompetenceCollectionTrait;

    private ?int $id = null;
    private ?Metier $metier = null;
    private ?Referentiel $referentiel = null;

    // repertoire DGAFP
    private ?string $definitionSynthetique = null;
    private ?string $competenceManageriale = null;
    private ?string $activite = null;
    private ?string $conditionsParticulieres = null;
    private ?string $tendanceEvolution = null;
    private ?string $impact = null;
    private ?string $codeCsp = null;
    private bool $fpt = false;
    private bool $fph = false;
    private bool $fpe = false;

    public function __construct()
    {
        $this->competences = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): void
    {
        $this->metier = $metier;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): void
    {
        $this->referentiel = $referentiel;
    }

    public function isFpt(): bool
    {
        return $this->fpt;
    }

    public function setFpt(bool $fpt): void
    {
        $this->fpt = $fpt;
    }

    public function isFph(): bool
    {
        return $this->fph;
    }

    public function setFph(bool $fph): void
    {
        $this->fph = $fph;
    }

    public function isFpe(): bool
    {
        return $this->fpe;
    }

    public function setFpe(bool $fpe): void
    {
        $this->fpe = $fpe;
    }

    public function getDefinitionSynthetique(): ?string
    {
        return $this->definitionSynthetique;
    }

    public function setDefinitionSynthetique(?string $definitionSynthetique): void
    {
        $this->definitionSynthetique = $definitionSynthetique;
    }

    public function getCompetenceManageriale(): ?string
    {
        return $this->competenceManageriale;
    }

    public function setCompetenceManageriale(?string $competenceManageriale): void
    {
        $this->competenceManageriale = $competenceManageriale;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): void
    {
        $this->activite = $activite;
    }

    public function getConditionsParticulieres(): ?string
    {
        return $this->conditionsParticulieres;
    }

    public function setConditionsParticulieres(?string $conditionsParticulieres): void
    {
        $this->conditionsParticulieres = $conditionsParticulieres;
    }

    public function getTendanceEvolution(): ?string
    {
        return $this->tendanceEvolution;
    }

    public function setTendanceEvolution(?string $tendanceEvolution): void
    {
        $this->tendanceEvolution = $tendanceEvolution;
    }

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): void
    {
        $this->impact = $impact;
    }

    public function getCodeCsp(): ?string
    {
        return $this->codeCsp;
    }

    public function setCodeCsp(?string $codeCsp): void
    {
        $this->codeCsp = $codeCsp;
    }


}