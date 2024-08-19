<?php

namespace FicheReferentiel\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineORMModule\Proxy\__CG__\Metier\Entity\Db\Metier;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheReferentiel implements HistoriqueAwareInterface, HasCompetenceCollectionInterface {
    use HistoriqueAwareTrait;
    use HasCompetenceCollectionTrait;

    private ?int $id = null;
    private ?Metier $metier = null;
    // private Collection $competences;

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


}