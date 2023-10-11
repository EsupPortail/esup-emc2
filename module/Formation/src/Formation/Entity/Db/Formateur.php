<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Formateur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const TYPE_FORMATEUR = 'Formateur';
    const TYPE_ORGANISME = 'Organisme';
    const TYPES = [
        Formateur::TYPE_FORMATEUR => 'Formateur·trice',
        Formateur::TYPE_ORGANISME => 'Organisme',
    ];

    private ?int $id = -1;
    private ?FormationInstance $instance = null;
    private string $type = Formateur::TYPE_FORMATEUR;
    private ?string $organisme = null;
    private ?string $prenom = null;
    private ?string $nom = null;
    private ?string $email = null;
    private ?string $telephone = null;
    private ?string $attachement = null;

    public function getId(): ?int
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    public function setOrganisme(?string $organisme): void
    {
        $this->organisme = $organisme;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(?string $attachement): void
    {
        $this->attachement = $attachement;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }


    public function getDenomination() : string
    {
        return match ($this->type) {
            Formateur::TYPE_FORMATEUR => $this->getPrenom() . " " . strtoupper($this->getNom()),
            Formateur::TYPE_ORGANISME => $this->organisme,
            default => "Type de formateur non prévu",
        };
    }
}