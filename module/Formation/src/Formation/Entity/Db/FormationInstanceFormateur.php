<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

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

}