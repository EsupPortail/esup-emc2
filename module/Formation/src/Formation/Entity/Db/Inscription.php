<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Fichier\Entity\Db\Fichier;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use UnicaenEnquete\Entity\HasEnqueteInterface;
use UnicaenEnquete\Entity\HasEnqueteTrait;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class Inscription implements
    HistoriqueAwareInterface, ResourceInterface,
    HasEtatsInterface, HasValidationsInterface, HasEnqueteInterface {
    use HistoriqueAwareTrait;
    use HasEtatsTrait, HasValidationsTrait, HasEnqueteTrait;

    public function getResourceId(): string
    {
        return 'Inscription';
    }

    const PRINCIPALE = 'principale';
    const COMPLEMENTAIRE = 'complementaire';

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?StagiaireExterne $stagiaire = null;
    private ?Session $session = null;
    private ?string $liste = null;

    private ?string $missions = null;
    private ?string $justificationAgent = null;
    private ?string $justificationResponsable = null;
    private ?string $justificationDrh = null;
    private ?string $justificationRefus = null;

    private bool $rqth = false;
    private ?string $precisionRqth = null;

    private Collection $presences;
    private ?InscriptionFrais $frais = null;
    private Collection $fichiers;

    private string $source;
    private string $idSource;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->presences = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
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

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): void
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

    public function getMissions(): ?string
    {
        return $this->missions;
    }

    public function setMissions(?string $missions): void
    {
        $this->missions = $missions;
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

    public function getLastValidation(): ?ValidationInstance
    {
        $validations = array_filter($this->getValidations(), function (ValidationInstance $validation) {
            return $validation->estNonHistorise();
        });
        if (empty($validations)) return null;

        usort($validations, function (ValidationInstance $a, ValidationInstance $b) {
            return $a->getHistoCreation() <=> $b->getHistoCreation();
        });
        return $validations[0];
    }

    public function isRqth(): bool
    {
        return $this->rqth;
    }

    public function setRqth(bool $rqth): void
    {
        $this->rqth = $rqth;
    }

    public function getPrecisionRqth(): ?string
    {
        return $this->precisionRqth;
    }

    public function setPrecisionRqth(?string $precisionRqth): void
    {
        $this->precisionRqth = $precisionRqth;
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

    public function getFrais(): ?InscriptionFrais
    {
        return $this->frais;
    }

    public function estPresence(string $code, Seance $seance): bool
    {
        /** @var Presence $presence */
        foreach ($this->presences as $presence) {
            if ($presence->estNonHistorise() && $presence->getStatut() === $code && $presence->getJournee() === $seance) {
                return true;
            }
        }
        return false;
    }

    public function hasAbsence(): bool
    {
        $codes = [Presence::PRESENCE_ABSENCE_NON_JUSTIFIEE, Presence::PRESENCE_ABSENCE_JUSTIFIEE];
        foreach ($this->presences as $presence) {
            if ($presence->estNonHistorise() && in_array($presence->getStatut(), $codes)) return true;
        }
        return false;
    }

    public function hasAllPresence(): bool
    {
        /** @var Presence $presence */
        foreach ($this->presences as $presence) {
            if ($presence->getStatut() === null || $presence->getStatut() === Presence::PRESENCE_NON_RENSEIGNEE) return false;
        }
        return true;
    }


    /** Gestion des fichiers  *****************************************************************************************/
    // TODO trait et interface ?

    /** @return Fichier[] */
    public function getFichiers(): array
    {
        return $this->fichiers->toArray();
    }

    public function addFichier(Fichier $fichier): void
    {
        $this->fichiers->add($fichier);
    }

    public function removeFichier(Fichier $fichier): void
    {
        $this->fichiers->removeElement($fichier);
    }

    public function hasFichier(Fichier $fichier): bool
    {
        return $this->fichiers->contains($fichier);
    }

    /** @return Fichier[] */
    public function getFichiersByNatureCode(string $code): array
    {
        $result = [];
        /** @var Fichier $fichier */
        foreach ($this->fichiers as $fichier) {
            if ($fichier->estNonHistorise() && $fichier->getNature()->getCode() === $code) {
                $result[] = $fichier;
            }
        }
        return $result;
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
    public function getStagiaireDenomination(bool $prenomFirst = true): string
    {
        if ($this->isInterne()) return $this->getAgent()->getDenomination($prenomFirst);
        if ($this->isExterne()) return $this->getStagiaire()->getDenomination($prenomFirst);
        return "Aucun·e stagiaire de trouvé·e pour l'inscription #".$this->getId();
    }

    /** @noinspection PhpUnsued */
    public function getStagiaireStructure(): string
    {
        if ($this->isInterne()) {
            $affectation = $this->getAgent()->getAffectationPrincipale($this->getSession()->getDebut(true));
            $structure = ($affectation)?$affectation->getStructure():null;
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

    /**  @noinspection PhpUnused */
    public function getDureePresence() : string
    {
        $sum = DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00');
        /** @var Presence[] $presences */
        $presences = array_filter($this->presences->toArray(), function (Presence $a) {
            return $a->estNonHistorise() and $a->isPresent();
        });
        foreach ($presences as $presence) {
            $journee = $presence->getJournee();
            if ($journee->getType() === Seance::TYPE_SEANCE) {
                $debut = DateTime::createFromFormat('d/m/Y H:i', $journee->getJour()->format('d/m/Y') . " " . $journee->getDebut());
                $fin = DateTime::createFromFormat('d/m/Y H:i', $journee->getJour()->format('d/m/Y') . " " . $journee->getFin());
                if ($debut instanceof DateTime and $fin instanceof DateTime) {
                    $duree = $debut->diff($fin);
                    $sum->add($duree);
                }
            }
            if ($journee->getType() === Seance::TYPE_VOLUME) {
                $volume = $journee->getVolume();
                try {
                    $temp = new DateInterval('PT' . $volume . 'H');
                } catch (Exception $e) {
                    throw new RuntimeException("Un problème est survenu lors de la création de l'intervale avec [PT".$volume."H]",0,$e);
                }
                $sum->add($temp);
            }
        }

        $result = $sum->diff(DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00'));
        $heures = ($result->d * 24 + $result->h);
        $minutes = ($result->i);
        $text = $heures . " heures " . (($minutes !== 0) ? ($minutes . " minutes") : "");
        return $text;
    }

    public function generateTag(): string
    {
        return 'Inscription_'. $this->getId();
    }
}