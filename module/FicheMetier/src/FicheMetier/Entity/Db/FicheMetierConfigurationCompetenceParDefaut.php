<?php

namespace FicheMetier\Entity\Db;


use Element\Entity\Db\Competence;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheMetierConfigurationCompetenceParDefaut implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Competence $competence = null;

    /** Remarque : on pourrait aussi ajouter un niveau de maitrise (on reproduit à l'identique pour le moment) **/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): void
    {
        $this->competence = $competence;
    }


}