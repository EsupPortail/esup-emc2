<?php

namespace Formation\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;

class StagiaireExterne implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $prenom = null;
    private ?string $nom = null;
    private ?DateTime $dateNaissance = null;
    private ?string $sexe = null;

    private ?string $structure = null;
    private ?string $email = null;
    private ?string $login = null;
    private ?User $utilisateur = null;

    private Collection $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateNaissance(): ?DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?DateTime $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): void
    {
        $this->sexe = $sexe;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(?string $structure): void
    {
        $this->structure = $structure;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    /** @return Inscription[] */
    public function getInscriptions(): array
    {
        return $this->inscriptions->toArray();
    }

    public function getDenomination(): string
    {
        return $this->getPrenom().' '.$this->getNom();
    }

    public function generateTag(): string
    {
        return "STAGIARE_" . $this->getId();
    }

}