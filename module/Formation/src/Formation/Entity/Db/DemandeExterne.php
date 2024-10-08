<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenObservation\Entity\Interface\HasObservationsInterface;
use UnicaenObservation\Entity\Trait\HasObservationsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class DemandeExterne implements HistoriqueAwareInterface, ResourceInterface, HasEtatsInterface, HasValidationsInterface, HasObservationsInterface
{
    use HistoriqueAwareTrait;
    use HasEtatsTrait;
    use HasValidationsTrait;
    use HasObservationsTrait;

    public function getResourceId(): string
    {
        return 'DemandeExterne';
    }

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?string $organisme = null;
    private ?string $contact = null;
    private ?string $missions = null;
    private ?string $pourquoi = null;
    private ?float $montant = null;
    private ?string $lieu = null;
    private ?DateTime $debut = null;
    private ?DateTime $fin = null;
    private bool $congeFormationSyndicale = false;
    private bool $priseEnCharge = true;
    private ?string $cofinanceur = null;
    private string $modalite = "présentiel";

    private ?Agent $agent = null;

    private ?string $justificationAgent = null;
    private ?string $justificationResponsable = null;
    private ?string $justificationGestionnaire = null;
    private ?string $justificationDrh = null;
    private ?string $justificationRefus = null;
    private ?Collection $devis;

    private Collection $gestionnaires;
    private Collection $sessions;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->gestionnaires = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->observations = new ArrayCollection();

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

    public function getMissions(): ?string
    {
        return $this->missions;
    }

    public function setMissions(?string $missions): void
    {
        $this->missions = $missions;
    }

    public function getPourquoi(): ?string
    {
        return $this->pourquoi;
    }

    public function setPourquoi(?string $pourquoi): void
    {
        $this->pourquoi = $pourquoi;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): void
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

    public function getJustificationGestionnaire(): ?string
    {
        return $this->justificationGestionnaire;
    }

    public function setJustificationGestionnaire(?string $justificationGestionnaire): void
    {
        $this->justificationGestionnaire = $justificationGestionnaire;
    }

    public function getJustificationDrh(): ?string
    {
        return $this->justificationDrh;
    }

    public function setJustificationDrh(?string $justificationDrh): void
    {
        $this->justificationDrh = $justificationDrh;
    }

    public function isPriseEnCharge(): bool
    {
        return $this->priseEnCharge;
    }

    public function setPriseEnCharge(bool $priseEnCharge): void
    {
        $this->priseEnCharge = $priseEnCharge;
    }

    public function isCongeFormationSyndicale(): bool
    {
        return $this->congeFormationSyndicale;
    }

    public function setCongeFormationSyndicale(bool $congeFormationSyndicale): void
    {
        $this->congeFormationSyndicale = $congeFormationSyndicale;
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

    public function isRqth(): bool
    {
        return false;
    }

    public function setRqth(bool $isRqth): void
    {}

    public function getPrecisionRqth(): void
    {}
    public function setPrecisionRqth(?string $precision = null): void
    {}

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

    /** @return Session[] */
    public function getSessions(): array
    {
        return $this->sessions->toArray();
    }

    public function addSession(Session $session): void
    {
        $this->sessions->add($session);
    }

    public function removeSession(Session $session): void
    {
        $this->sessions->removeElement($session);
    }

    /** GESTION DES GESTIONNAIRES *************************************************************************************/

    /** @return UserInterface[] */
    public function getGestionnaires(): array
    {
        return $this->gestionnaires->toArray();
    }

    public function hasGestionnaire(UserInterface $gestionnaire): bool
    {
        return $this->gestionnaires->contains($gestionnaire);
    }

    public function addGestionnaire(UserInterface $gestionnaire): void
    {
        if (!$this->hasGestionnaire($gestionnaire)) $this->gestionnaires->add($gestionnaire);
    }

    public function removeGestionnaire(UserInterface $gestionnaire): void
    {
        if ($this->hasGestionnaire($gestionnaire)) $this->gestionnaires->removeElement($gestionnaire);
    }

    /** MACRO ET PRETTYPRINT ******************************************************************************************/

    public function toStringDescription(): string
    {
        $text = "<dl class='row'>";
        if ($this->getOrganisme()) {
            $text .= "<dt class='col-md-4'>Organisme</dt><dd>" . $this->getOrganisme() . "</dd>";
        }
        if ($this->getContact()) {
            $text .= "<dt class='col-md-4'>Contact</dt><dd>" . $this->getContact() . "</dd>";
        }
        if ($this->getLieu()) {
            $text .= "<dt class='col-md-4'>Lieu</dt><dd>" . $this->getLieu() . "</dd>";
        }
        if ($this->getMontant()) {
            $text .= "<dt class='col-md-4'>Montant</dt><dd>" . $this->getMontant() . "</dd>";
        }
        $text .= "</dl>";
        return $text;
    }
}