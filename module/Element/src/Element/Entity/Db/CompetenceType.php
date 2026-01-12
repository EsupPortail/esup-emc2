<?php

namespace Element\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CompetenceType implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const CODE_CONNAISSANCE    = 'CONN';
    const CODE_OPERATIONNELLE  = 'OPER';
    const CODE_COMPORTEMENTALE = 'COMP';
    const CODE_SPECIFIQUE = 'SPEC';

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelle = null;
    private Collection $competences;
    private ?int $ordre = null;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    /** @return Competence[] */
    public function getCompetences(): array
    {
        return $this->competences->toArray();
    }

    public function addCompetence(Competence $competence): void
    {
        $this->competences->add($competence);
    }

    public function removeCompetence(Competence $competence) : void
    {
        $this->competences->removeElement($competence);
    }

    public function hasCompetence(Competence $competence): bool
    {
        return $this->competences->contains($competence);
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }

}