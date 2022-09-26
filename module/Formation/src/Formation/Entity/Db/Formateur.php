<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Formateur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private int $id = -1;
    private ?FormationInstance $instance = null;
    private ?string $prenom = null;
    private ?string $nom = null;
    private ?string $email = null;
    private ?string $attachement = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInstance(): ?FormationInstance
    {
        return $this->instance;
    }

    public function setInstance(?FormationInstance $instance): void
    {
        $this->instance = $instance;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(?string $attachement): void
    {
        $this->attachement = $attachement;
    }

}