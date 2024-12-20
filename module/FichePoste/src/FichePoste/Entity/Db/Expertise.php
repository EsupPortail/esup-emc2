<?php

namespace FichePoste\Entity\Db;


use Application\Entity\Db\FichePoste;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Expertise implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FichePoste $ficheposte  = null;
    private ?string $libelle = null;
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFicheposte(): ?FichePoste
    {
        return $this->ficheposte;
    }

    public function setFicheposte(?FichePoste $ficheposte): void
    {
        $this->ficheposte = $ficheposte;
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
}
