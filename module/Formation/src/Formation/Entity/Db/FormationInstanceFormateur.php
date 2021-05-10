<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstanceFormateur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FormationInstance */
    private $instance;
    /** @var string */
    private $prenom;
    /** @var string */
    private $nom;
    /** @var string|null */
    private $email;
    /** @var string */
    private $attachement;
    /** @var float */
    private $volume;
    /** @var float */
    private $montant;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return FormationInstance
     */
    public function getInstance(): FormationInstance
    {
        return $this->instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstanceFormateur
     */
    public function setInstance(FormationInstance $instance): FormationInstanceFormateur
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return FormationInstanceFormateur
     */
    public function setPrenom(string $prenom): FormationInstanceFormateur
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return FormationInstanceFormateur
     */
    public function setEmail(?string $email): FormationInstanceFormateur
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $nom
     * @return FormationInstanceFormateur
     */
    public function setNom(string $nom): FormationInstanceFormateur
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    /**
     * @param string|null $attachement
     * @return FormationInstanceFormateur
     */
    public function setAttachement(?string $attachement): FormationInstanceFormateur
    {
        $this->attachement = $attachement;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getVolume(): ?float
    {
        return $this->volume;
    }

    /**
     * @param float|null $volume
     * @return FormationInstanceFormateur
     */
    public function setVolume(?float $volume): FormationInstanceFormateur
    {
        $this->volume = $volume;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMontant(): ?float
    {
        return $this->montant;
    }

    /**
     * @param float|null $montant
     * @return FormationInstanceFormateur
     */
    public function setMontant(?float $montant): FormationInstanceFormateur
    {
        $this->montant = $montant;
        return $this;
    }
}