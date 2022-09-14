<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Fichier\Entity\Db\Fichier;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsTrait;

class DemandeExterne implements HistoriqueAwareInterface, ResourceInterface {
    use HistoriqueAwareTrait;
    use HasValidationsTrait;

    public function getResourceId() : string
    {
        return 'DemandeExterne';
    }

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?string $organisme = null;
    private ?string $contact = null;
    private ?string $pourquoi = null;
    private ?string $montant = null;
    private ?string $lieu = null;
    private ?DateTime $debut = null;
    private ?DateTime $fin = null;
    private bool $priseEnCharge = true;
    private ?string $cofinanceur = null;

    private ?Agent $agent = null;
    private ?Etat $etat = null;

    private ?string $justificationAgent = null;
    private ?string $justificationResponsable = null;
    private ?string $justificationRefus = null;

    /** @var ?ArrayCollection */
    private $devis = null;

    /**
     * @return int
     */
    public function getId(): ?int
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
    public function getJustificationAgent(): ?string
    {
        return $this->justificationAgent;
    }

    /**
     * @param string|null $justificationAgent
     */
    public function setJustificationAgent(?string $justificationAgent): void
    {
        $this->justificationAgent = $justificationAgent;
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

    public function getJustificationResponsable(): ?string
    {
        return $this->justificationResponsable;
    }

    public function setJustificationResponsable(?string $justificationResponsable): void
    {
        $this->justificationResponsable = $justificationResponsable;
    }

    public function getJustificationRefus(): ?string
    {
        return $this->justificationRefus;
    }

    public function setJustificationRefus(?string $justificationRefus): void
    {
        $this->justificationRefus = $justificationRefus;
    }

    /**
     * @return Fichier[]
     */
    public function getDevis() : array
    {
        return $this->devis->toArray();
    }

    public function addDevis($fichier)
    {
        $this->devis->add($fichier);
    }
}