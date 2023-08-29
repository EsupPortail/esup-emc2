<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsTrait;

class DemandeExterne implements HistoriqueAwareInterface, ResourceInterface, HasEtatsInterface
{
    use HistoriqueAwareTrait;
    use HasEtatsTrait;
    use HasValidationsTrait;

    public function getResourceId(): string
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
    private string $modalite = "présentiel";

    private ?Agent $agent = null;

    private ?string $justificationAgent = null;
    private ?string $justificationResponsable = null;
    private ?string $justificationRefus = null;
    private ?Collection $devis;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->devis = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    public function setOrganisme(?string $organisme): void
    {
        $this->organisme = $organisme;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): void
    {
        $this->contact = $contact;
    }

    public function getPourquoi(): ?string
    {
        return $this->pourquoi;
    }

    public function setPourquoi(?string $pourquoi): void
    {
        $this->pourquoi = $pourquoi;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): void
    {
        $this->montant = $montant;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): void
    {
        $this->lieu = $lieu;
    }

    public function getDebut(): ?DateTime
    {
        return $this->debut;
    }

    public function setDebut(?DateTime $debut): void
    {
        $this->debut = $debut;
    }

    /**
     * Utiliser por les macros
     * @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction)
     */
    public function getDebutAsString(): string
    {
        if ($this->getDebut() === null) return "Date de début absente";
        return $this->getDebut()->format('d/m/Y');
    }

    public function getFin(): ?DateTime
    {
        return $this->fin;
    }

    /**
     * Utiliser por les macros
     * @SuppressWarnings(Generic.CodeAnalysis.UnusedFunction)
     **/
    public function getFinAsString(): string
    {
        if ($this->getFin() === null) return "Date de fin absente";
        return $this->getFin()->format('d/m/Y');
    }

    public function getPeriodeAsString(): string
    {
        if ($this->getDebut() === $this->getFin()) return $this->getDebutAsString();
        return $this->getDebutAsString() . " au " . $this->getFinAsString();
    }

    public function setFin(?DateTime $fin): void
    {
        $this->fin = $fin;
    }

    /**
     * @return string
     */
    public function getModalite(): string
    {
        return $this->modalite;
    }

    /**
     * @param string $modalite
     */
    public function setModalite(string $modalite): void
    {
        $this->modalite = $modalite;
    }

    public function getJustificationAgent(): ?string
    {
        return $this->justificationAgent;
    }

    public function setJustificationAgent(?string $justificationAgent): void
    {
        $this->justificationAgent = $justificationAgent;
    }

    public function isPriseEnCharge(): bool
    {
        return $this->priseEnCharge;
    }

    public function setPriseEnCharge(bool $priseEnCharge): void
    {
        $this->priseEnCharge = $priseEnCharge;
    }

    public function getCofinanceur(): ?string
    {
        return $this->cofinanceur;
    }

    public function setCofinanceur(?string $cofinanceur): void
    {
        $this->cofinanceur = $cofinanceur;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function generateTag(): string
    {
        return 'DemandeExterne_' . $this->getId();
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

    public function getDevis(): array
    {
        return $this->devis->toArray();
    }

    public function addDevis($fichier): void
    {
        $this->devis->add($fichier);
    }
}