<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsTrait;

class DemandeExterne implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;
    use HasValidationsTrait;

    private int $id = -1;
    private ?string $libelle = null;
    private ?string $organisme = null;
    private ?string $contact = null;
    private ?string $pourquoi = null;
    private ?string $montant = null;
    private ?string $lieu = null;
    private ?DateTime $debut = null;
    private ?DateTime $fin = null;
    private ?string $motivation = null;
    private bool $priseEnCharge = false;
    private ?string $cofinanceur = null;

    private ?Agent $agent = null;
    private ?Etat $etat = null;

//    public function __construct()
//    {
//        $this->validations = new ArrayCollection();
//    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     */
    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string|null
     */
    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    /**
     * @param string|null $organisme
     */
    public function setOrganisme(?string $organisme): void
    {
        $this->organisme = $organisme;
    }

    /**
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->contact;
    }

    /**
     * @param string|null $contact
     */
    public function setContact(?string $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return string|null
     */
    public function getPourquoi(): ?string
    {
        return $this->pourquoi;
    }

    /**
     * @param string|null $pourquoi
     */
    public function setPourquoi(?string $pourquoi): void
    {
        $this->pourquoi = $pourquoi;
    }

    /**
     * @return string|null
     */
    public function getMontant(): ?string
    {
        return $this->montant;
    }

    /**
     * @param string|null $montant
     */
    public function setMontant(?string $montant): void
    {
        $this->montant = $montant;
    }

    /**
     * @return string|null
     */
    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    /**
     * @param string|null $lieu
     */
    public function setLieu(?string $lieu): void
    {
        $this->lieu = $lieu;
    }

    /**
     * @return DateTime|null
     */
    public function getDebut(): ?DateTime
    {
        return $this->debut;
    }

    /**
     * @param DateTime|null $debut
     */
    public function setDebut(?DateTime $debut): void
    {
        $this->debut = $debut;
    }

    /**
     * @return DateTime|null
     */
    public function getFin(): ?DateTime
    {
        return $this->fin;
    }

    /**
     * @param DateTime|null $fin
     */
    public function setFin(?DateTime $fin): void
    {
        $this->fin = $fin;
    }

    /**
     * @return string|null
     */
    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    /**
     * @param string|null $motivation
     */
    public function setMotivation(?string $motivation): void
    {
        $this->motivation = $motivation;
    }

    /**
     * @return bool
     */
    public function isPriseEnCharge(): bool
    {
        return $this->priseEnCharge;
    }

    /**
     * @param bool $priseEnCharge
     */
    public function setPriseEnCharge(bool $priseEnCharge): void
    {
        $this->priseEnCharge = $priseEnCharge;
    }

    /**
     * @return string|null
     */
    public function getCofinanceur(): ?string
    {
        return $this->cofinanceur;
    }

    /**
     * @param string|null $cofinanceur
     */
    public function setCofinanceur(?string $cofinanceur): void
    {
        $this->cofinanceur = $cofinanceur;
    }

    /**
     * @return Agent|null
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent|null $agent
     */
    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    /**
     * @return Etat|null
     */
    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     */
    public function setEtat(?Etat $etat): void
    {
        $this->etat = $etat;
    }

    public function generateTag() : string
    {
        return 'DemandeExterne_' . $this->getId() ;
    }


}