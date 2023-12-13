<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class Inscription implements HistoriqueAwareInterface, HasEtatsInterface, HasValidationsInterface {
    use HistoriqueAwareTrait;
    use HasEtatsTrait;
    use HasValidationsTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?StagiaireExterne $stagiaire = null;
    private ?FormationInstance $session = null;
    private ?string $liste = null;

    private ?string $justificationAgent = null;
    private ?string $justificationResponsable = null;
    private ?string $justificationDrh = null;
    private ?string $justificationRefus = null;

    private ?DateTime $validationEnquete = null;
    private Collection $reponsesEnquete;

//    TODO ...
//    private Collection $presences;
    private ?InscriptionFrais $frais = null;

    private string $source;
    private string $idSource;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->reponsesEnquete = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getStagiaire(): ?StagiaireExterne
    {
        return $this->stagiaire;
    }

    public function setStagiaire(?StagiaireExterne $stagiaire): void
    {
        $this->stagiaire = $stagiaire;
    }

    public function getSession(): ?FormationInstance
    {
        return $this->session;
    }

    public function setSession(?FormationInstance $session): void
    {
        $this->session = $session;
    }

    public function getListe(): ?string
    {
        return $this->liste;
    }

    public function setListe(?string $liste): void
    {
        $this->liste = $liste;
    }

    public function getJustificationAgent(): ?string
    {
        return $this->justificationAgent;
    }

    public function setJustificationAgent(?string $justificationAgent): void
    {
        $this->justificationAgent = $justificationAgent;
    }

    public function getJustificationResponsable(): ?string
    {
        return $this->justificationResponsable;
    }

    public function setJustificationResponsable(?string $justificationResponsable): void
    {
        $this->justificationResponsable = $justificationResponsable;
    }

    public function getJustificationDrh(): ?string
    {
        return $this->justificationDrh;
    }

    public function setJustificationDrh(?string $justificationDrh): void
    {
        $this->justificationDrh = $justificationDrh;
    }

    public function getJustificationRefus(): ?string
    {
        return $this->justificationRefus;
    }

    public function setJustificationRefus(?string $justificationRefus): void
    {
        $this->justificationRefus = $justificationRefus;
    }

    public function getValidationEnquete(): ?DateTime
    {
        return $this->validationEnquete;
    }

    public function setValidationEnquete(?DateTime $validationEnquete): void
    {
        $this->validationEnquete = $validationEnquete;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getIdSource(): string
    {
        return $this->idSource;
    }

    public function setIdSource(string $idSource): void
    {
        $this->idSource = $idSource;
    }

    /** PREDICATS ET RACCOURCIS ***************************************************************************************/

    public function isInterne(): bool
    {
        return ($this->agent !== null);
    }

    public function isExterne(): bool
    {
        return ($this->stagiaire !== null);
    }

    public function getIndividu(): Agent|StagiaireExterne|null
    {
        if ($this->isInterne()) return  $this->getAgent();
        if ($this->isExterne()) return  $this->getStagiaire();
        return null;
    }

    /** MACROS ********************************************************************************************************/

    /** @noinspection PhpUnsued */
    public function getStagiaireDenomination(): string
    {
        if ($this->isInterne()) return $this->getAgent()->getDenomination(true);
        if ($this->isExterne()) return $this->getStagiaire()->getDenomination();
        return "Aucun·e stagiaire de trouvé·e pour l'inscription #".$this->getId();
    }

    /** @noinspection PhpUnsued */
    public function getStagiaireStructure(): string
    {
        if ($this->isInterne()) {
            $affectation = $this->getAgent()->getAffectationPrincipale($this->getSession()->getDebut(true));
            $structure = $affectation->getStructure();
            if ($structure) return $structure->getLibelleLong();
            return "Aucune structure de connue";
        }
        if ($this->isExterne()) {
            $structure = $this->getStagiaire()->getStructure();
            if ($structure) return $structure;
            return "Aucune structure de connue";
        }
        return "Aucun·e stagiaire de trouvé·e pour l'inscription #".$this->getId();
    }
}