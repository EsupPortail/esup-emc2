<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Formation\Entity\Db\Interfaces\HasInscriptionsInterfaces;
use Formation\Entity\Db\Traits\HasInscriptionsTrait;
use Formation\Provider\Etat\SessionEtats;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenEvenement\Entity\HasEvenementsInterface;
use UnicaenEvenement\Entity\HasEvenementsTrait;
use UnicaenMail\Entity\HasMailsInterface;
use UnicaenMail\Entity\HasMailsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenUtilisateur\Entity\Db\UserInterface;

class Session implements
    HasInscriptionsInterfaces,
    HasEvenementsInterface, HasMailsInterface, HasEtatsInterface, HasSourceInterface,
    HistoriqueAwareInterface, ResourceInterface
{
    use HasInscriptionsTrait;
    use HasEvenementsTrait, HasMailsTrait, HasEtatsTrait, HasSourceTrait;
    use HistoriqueAwareTrait;

    public function getResourceId(): string
    {
        return 'Session';
    }

    const TYPE_INTERNE = "formation interne";
    const TYPE_EXTERNE = "formation externe";
    const TYPE_REGIONALE = "formation régionale";
    const RATTACHEMENT_PREVENTION = 'prévention';
    const RATTACHEMENT_BIBLIOTHEQUE = 'bibliotheque';

    private ?int $id = null;
    private ?Formation $formation = null;
    private ?string $complement = null;
    private ?bool $autoInscription = null;

    private int $nbPlacePrincipale = -1;
    private int $nbPlaceComplementaire = -1;
    private ?DateTime $dateClotureInscription = null;
    private ?string $lieu = null;
    private ?string $type = null;
    private bool $affichage = true;

    private ?float $coutHt = null;
    private ?float $coutTtc = null;
    private ?float $coutVacation = null;
    private ?float $recetteTtc = null;


    private Collection $journees;
    private Collection $inscrits;
    private Collection $externes;
    private Collection $formateurs;
    private Collection $demandes;
    private Collection $gestionnaires;

    private ?SessionParametre $parametre = null;



    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->mails = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->journees = new ArrayCollection();
        $this->inscrits = new ArrayCollection();
        $this->externes = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->gestionnaires = new ArrayCollection();

        $this->formateurs = new ArrayCollection();

    }

    /**
     * @return string
     */
    public function generateTag(): string
    {
        return 'FormationInstance_' . $this->getId();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Formation
     */
    public function getFormation(): Formation
    {
        return $this->formation;
    }

    /**
     * @param Formation|null $formation
     * @return Session
     */
    public function setFormation(?Formation $formation): Session
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @param string|null $complement
     * @return Session
     */
    public function setComplement(?string $complement): Session
    {
        $this->complement = $complement;
        return $this;
    }

    /** PLACE SUR LISTE  **********************************************************************************************/

    /**
     * @return int
     */
    public function getNbPlacePrincipale(): int
    {
        return $this->nbPlacePrincipale;
    }

    /**
     * @param int $nbPlacePrincipale
     * @return Session
     */
    public function setNbPlacePrincipale(int $nbPlacePrincipale): Session
    {
        $this->nbPlacePrincipale = $nbPlacePrincipale;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbPlaceComplementaire(): int
    {
        return $this->nbPlaceComplementaire;
    }

    /**
     * @param int $nbPlaceComplementaire
     * @return Session
     */
    public function setNbPlaceComplementaire(int $nbPlaceComplementaire): Session
    {
        $this->nbPlaceComplementaire = $nbPlaceComplementaire;
        return $this;
    }

    public function getDateClotureInscription(): ?DateTime
    {
        return $this->dateClotureInscription;
    }

    public function setDateClotureInscription(?DateTime $dateClotureInscription): void
    {
        $this->dateClotureInscription = $dateClotureInscription;
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
     * @return Session
     */
    public function setLieu(?string $lieu): Session
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Session
     */
    public function setType(?string $type): Session
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoInscription(): bool
    {
        return ($this->autoInscription) ?: false;
    }

    /**
     * @param bool $autoInscription
     * @return Session
     */
    public function setAutoInscription(bool $autoInscription = false): Session
    {
        $this->autoInscription = $autoInscription;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAffichage(): bool
    {
        return $this->affichage;
    }

    /**
     * @param bool $affichage
     */
    public function setAffichage(bool $affichage): void
    {
        $this->affichage = $affichage;
    }



    /** FORMATEURS ****************************************************************************************************/

    /**
     * @return Formateur[]|null
     */
    public function getFormateurs(bool $withHisto = false): ?array
    {
        if (!isset($this->formateurs)) return null;
        /** @var Formateur[] $formateurs */
        $formateurs = $this->formateurs->toArray();
        if (!$withHisto) $formateurs = array_filter($formateurs, function (Formateur $formateur) {return $formateur->estNonHistorise();});
        usort($formateurs, function (Formateur $a, Formateur $b) { return $a->getDenomination() <=> $b->getDenomination(); });
        return $formateurs;
    }

    public function addFormateur(Formateur $formateur): void
    {
        $this->formateurs->add($formateur);
    }

    public function removeFormateur(Formateur $formateur): void
    {
        $this->formateurs->removeElement($formateur);
    }

    /** DEMANDES EXTERNES *********************************************************************************************/

    /** @return DemandeExterne[] */
    public function getDemandesExternes(): array
    {
        return $this->demandes->toArray();
    }

    /** SÉANCES *******************************************************************************************************/

    /**
     * @return Seance[]|null
     */
    public function getSeances(bool $withHisto = false): ?array
    {
        if (!isset($this->journees)) return null;
        $seances = $this->journees->toArray();
        if (!$withHisto) $seances = array_filter($seances, function(Seance $seance) { return $seance->estNonHistorise();});
        usort($seances, function (Seance $a, Seance $b) {
            $debutA = $a->getDateDebut();
            $debutB = $b->getDateDebut();
            return $debutA <=> $debutB;
        });
        return $seances;
    }

    public function getDebut(bool $datetime = false): string|DateTime|null
    {
        $minimum = null;
        /** @var Seance $journee */
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise()) {
                if ($journee->getType() === Seance::TYPE_SEANCE) {
                    $dateString = $journee->getJour()->format('d/m/Y'). " " . $journee->getDebut();
                    $date = DateTime::createFromFormat("d/m/Y H:i", $dateString);
                    if ($minimum === null or $date < $minimum) $minimum = $date;
                }
                if ($journee->getType() === Seance::TYPE_VOLUME) {
                    if ($journee->getVolumeDebut()) {
                        $debut = $journee->getVolumeDebut()->format("Y/m/d");
                        if ($minimum === null or $debut < $minimum) $minimum = $debut;
                    }
                }
            }
        }
        if ($minimum !== null) {
            if ($datetime) return $minimum;
            else return $minimum->format('d/m/Y H:i');
        }
        return $minimum;
    }

    public function getFin(bool $datetime = false): string|DateTime|null
    {
        $maximum = null;
        /** @var Seance $journee */
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise()) {
                if ($journee->getType() === Seance::TYPE_SEANCE) {
                    $dateString = $journee->getJour()->format('d/m/Y'). " " . $journee->getFin();
                    $date = DateTime::createFromFormat("d/m/Y H:i", $dateString);
                    if ($maximum === null or $date > $maximum) $maximum = $date;
                }
                if ($journee->getType() === Seance::TYPE_VOLUME) {
                    if ($journee->getVolumeDebut()) {
                        $fin = $journee->getVolumeFin()->format("Y/m/d");
                        if ($maximum === null or $fin > $maximum) $maximum = $fin;
                    }
                }
            }
        }
        if ($maximum !== null) {
            if ($datetime) return $maximum;
            else return $maximum->format('d/m/Y H:i');
        }
        return $maximum;
    }

    public function hasJournee(): bool
    {
        /** @var Seance $journee */
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise()) return true;
        }
        return false;
    }
    /** INSCRIT *******************************************************************************************************/

    /**
     * @return Inscription[]
     */
    public function getListePrincipale(): array
    {
        $array = array_filter($this->inscriptions->toArray(), function (Inscription $a) {
            return ($a->getListe() === Inscription::PRINCIPALE and $a->estNonHistorise());
        });
        usort($array, function (Inscription $a, Inscription $b) {
            return $a->getStagiaireDenomination() <=> $b->getStagiaireDenomination();
        });
        return $array;
    }

    /**
     * @return Inscription[]
     */
    public function getListeComplementaire(): array
    {
        $array = array_filter($this->inscriptions->toArray(), function (Inscription $a) {
            return ($a->getListe() === Inscription::COMPLEMENTAIRE and $a->estNonHistorise());
        });
        usort($array, function (Inscription $a, Inscription $b) {
            return $a->getStagiaireDenomination() <=> $b->getStagiaireDenomination();
        });
        return $array;
    }

    /**
     * @return Inscription[]
     */
    public function getListeHistorisee(): array
    {
        $array = array_filter($this->inscriptions->toArray(), function (Inscription $a) {
            return $a->estHistorise();
        });
        usort($array, function (Inscription $a, Inscription $b) {
            return $a->getStagiaireDenomination() <=> $b->getStagiaireDenomination();
        });
        return $array;
    }

    public function isListePrincipaleComplete(): bool
    {
        $liste = $this->getListePrincipale();
        return (count($liste) >= $this->getNbPlacePrincipale());
    }

    public function isListeComplementaireComplete(): bool
    {
        $liste = $this->getListeComplementaire();
        return (count($liste) >= $this->getNbPlaceComplementaire());
    }

    /**
     * @return string|null
     */
    public function getListeDisponible(): ?string
    {
        $liste = null;
        if ($liste === null and !$this->isListePrincipaleComplete()) $liste = Inscription::PRINCIPALE;
        if ($liste === null and !$this->isListeComplementaireComplete()) $liste = Inscription::COMPLEMENTAIRE;
        return $liste;
    }

    public function hasIndividu(Agent|StagiaireExterne $individu): bool
    {
        $isStagiaire = ($individu instanceof StagiaireExterne);
        $isAgent = ($individu instanceof Agent);
        /** @var Inscription $inscription */
        foreach ($this->inscriptions as $inscription) {
            if ($isStagiaire && $individu === $inscription->getStagiaire()) return true;
            if ($isAgent && $individu === $inscription->getAgent()) return true;
        }
        return false;
    }

    /** COUT DE LA FORMATION *********************************************************************************/

    /**
     * @return float|null
     */
    public function getCoutHt(): ?float
    {
        return $this->coutHt;
    }

    /**
     * @param float|null $coutHt
     */
    public function setCoutHt(?float $coutHt): void
    {
        $this->coutHt = $coutHt;
    }

    /**
     * @return float|null
     */
    public function getCoutTtc(): ?float
    {
        return $this->coutTtc;
    }

    /**
     * @param float|null $coutTtc
     */
    public function setCoutTtc(?float $coutTtc): void
    {
        $this->coutTtc = $coutTtc;
    }

    public function getCoutVacation(): ?float
    {
        return $this->coutVacation;
    }

    public function setCoutVacation(?float $coutVacation): void
    {
        $this->coutVacation = $coutVacation;
    }

    public function getRecetteTtc(): ?float
    {
        return $this->recetteTtc;
    }

    public function setRecetteTtc(?float $recetteTtc): void
    {
        $this->recetteTtc = $recetteTtc;
    }

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


    /** PARAMETRES ***************************************************************************************************/

    public function getParametre(): ?SessionParametre
    {
        return $this->parametre;
    }

    public function setParametre(?SessionParametre $parametre): void
    {
        $this->parametre = $parametre;
    }

    public function isMailActive(): bool
    {
        if ($this->parametre === null) return true;
        return $this->parametre->isMailActive();
    }

    public function isEvenementActive(): bool
    {
        if ($this->parametre === null) return true;
        return $this->parametre->isEvenementActive();
    }

    public function isEnqueteActive(): bool
    {
        if ($this->parametre === null) return true;
        return $this->parametre->isEnqueteActive();
    }

    public function isEmargementActive(): bool
    {
        if ($this->parametre === null) return true;
        return $this->parametre->isEmargementActive();
    }

    /** PREDICAT D'ETAT *********************************************************************************************/

    public function estPreparation(): bool
    {
        $etatCode = $this->getEtatActif() ? $this->getEtatActif()->getType()->getCode() : null;
        return in_array($etatCode, SessionEtats::ETATS_PREPARATION);
    }

    public function estPrete(): bool
    {
        $etatCode = $this->getEtatActif() ? $this->getEtatActif()->getType()->getCode() : null;
        return (
            $etatCode === SessionEtats::ETAT_FORMATION_CONVOCATION
        );
    }

    public function estRealisee(): bool
    {
        $etatCode = $this->getEtatActif() ? $this->getEtatActif()->getType()->getCode() : null;
        return (
            $etatCode === SessionEtats::ETAT_ATTENTE_RETOURS ||
            $etatCode === SessionEtats::ETAT_CLOTURE_INSTANCE
        );
    }

    /** Fonctions pour les macros **********************************************************************************/

    /** @noinspection PhpUnused */
    public function getInstanceLibelle(): string
    {
        return $this->getFormation()->getLibelle();
    }

    /** @noinspection PhpUnused */
    public function getLieuString(): string
    {
        return ($this->getLieu()) ? " à " . $this->getLieu() : "";
    }

    /** @noinspection PhpUnused */
    public function getInstanceCode(): string
    {
        return $this->getFormation()->getId() . "/" . $this->getId();
    }

    /** @noinspection PhpUnused */
    public function getListeFormateurs(): string
    {
        /** @var Formateur[] $formateurs */
        $formateurs = $this->getFormateurs();
        usort($formateurs, function (Formateur $a, Formateur $b) {
            return ($a->getNom() . " " . $a->getPrenom()) <=> ($b->getNom() . " " . $b->getPrenom());
        });

        $text = "<table style='width:100%;'>";
        $text .= "<thead>";
        $text .= "<tr style='border-bottom:1px solid black;'>";
        $text .= "<th>Dénomination  </th>";
        $text .= "<th>Structure de rattachement / Organisme  </th>";
        $text .= "</tr>";
        $text .= "</thead>";
        $text .= "<tbody>";
        foreach ($formateurs as $formateur) {
            $text .= "<tr>";
            $text .= "<td>" . $formateur->getPrenom() . " " . $formateur->getNom() . "</td>";
            $text .= "<td>" . $formateur->getAttachement() . "</td>";
            $text .= "</tr>";
        }
        $text .= "</tbody>";
        $text .= "</table>";

        return $text;
    }

    /** @noinspection PhpUnused */
    public function getListeJournees(): string
    {
        /** @var Seance[] $journees */
        $journees = $this->getSeances();
        $seances = array_filter($journees, function (Seance $seance) { return $seance->getType() === Seance::TYPE_SEANCE;});
        $volumes = array_filter($journees, function (Seance $seance) { return $seance->getType() === Seance::TYPE_VOLUME;});

            //usort($journees, function (FormationInstanceJournee $a, FormationInstanceJournee $b) { return $a <=> $b;});

        $text = '';
        if (!empty($seances)) {
            $text .= "<h3>Séance·s de formation</h3>";
            $text .= "<table style='width:100%;'>";
            $text .= "<thead>";
            $text .= "<tr style='border-bottom:1px solid black;'>";
            $text .= "<th>Date</th>";
            $text .= "<th>de</th>";
            $text .= "<th>à</th>";
            $text .= "<th>Lieu ou lien</th>";
            $text .= "</tr>";
            $text .= "</thead>";
            $text .= "<tbody>";
            foreach ($seances as $seance) {
                $text .= "<tr>";
                $text .= "<td>" . $seance->getJour()->format('d/m/Y') . "</td>";
                $text .= "<td>" . $seance->getDebut() . "</td>";
                $text .= "<td>" . $seance->getFin() . "</td>";
                $text .= "<td>";
                if ($seance->getType() === Seance::TYPE_SEANCE) {
                    if ($seance->getLieu() !== null) {
                        $text .= $seance->getLieu()->getLibelle() . " - " . $seance->getLieu()->getBatiment() . " / ";
                        $text .= $seance->getLieu()->getCampus() . " - " . $seance->getLieu()->getVille();
                    } else {
                        $text .= "Lieu non renseigné";
                    }
                }
                if ($seance->getType() === Seance::TYPE_VOLUME) {
                    $text .= $seance->getLien();
                }
                $text .= "</td>";
                $text .= "</tr>";
            }
            $text .= "</tbody>";
            $text .= "</table>";
        }

        if (!empty($volumes)) {
            $text .= "<h3>Volume·s horarire·s </h3>";
            $text .= "<table style='width:100%;'>";
            $text .= "<thead>";
            $text .= "<tr style='border-bottom:1px solid black;'>";
            $text .= "<th>Volume  </th>";
            $text .= "<th>Période  </th>";
            $text .= "<th>Lieu  </th>";
            $text .= "</tr>";
            $text .= "</thead>";
            $text .= "<tbody>";
            foreach ($volumes as $volume) {
                $text .= "<tr>";
                $text .= "<td>" . $volume->getVolume() . " heures </td>";
                $text .= "<td>" . $volume->getVolumeDebut()->format('d/m/Y') . " au  ".$volume->getVolumeFin()->format('d/m/Y')."</td>";
                $text .= "<td>" . $volume->getLieu() . "</td>";
                $text .= "</tr>";
            }
            $text .= "</tbody>";
            $text .= "</table>";
        }
        return $text;
    }

    /** @noinspection PhpUnused */
    public function getPeriode(): string
    {
        if ($this->getDebut() === null or $this->getFin() === null) return "formation sans date";
        if ($this->getDebut() === $this->getFin()) return $this->getDebut();
        return $this->getDebut() . " au " . $this->getFin();
    }

    /** @noinspection PhpUnused */
    public function getListeComplementaireAgents(): string
    {
        $inscrits = $this->getListeComplementaire();
        $inscrits = array_filter($inscrits, function (Inscription $a) {
            return $a->estNonHistorise();
        });
        usort($inscrits, function (Inscription $a, Inscription $b) {
            return ($a->getAgent()->getNomUsuel() . " " . $a->getAgent()->getPrenom()) <=> ($b->getAgent()->getNomUsuel() . " " . $b->getAgent()->getPrenom());
        });

        $text = "<table style='width:100%;'>";
        $text .= "<thead>";
        $text .= "<tr style='border-bottom:1px solid black;'>";
        $text .= "<th>Dénomination  </th>";
        $text .= "<th>Affectation principale  </th>";
        $text .= "<th>Adresse électronique  </th>";
        $text .= "</tr>";
        $text .= "</thead>";
        $text .= "<tbody>";
        foreach ($inscrits as $inscrit) {
            $text .= "<tr>";
            $text .= "<td>" . $inscrit->getAgent()->getNomUsuel() . " " . $inscrit->getAgent()->getPrenom() . "</td>";
            $text .= "<td>" . (($inscrit->getAgent()->getAffectationPrincipale() and $inscrit->getAgent()->getAffectationPrincipale()->getStructure()) ? $inscrit->getAgent()->getAffectationPrincipale()->getStructure()->getLibelleLong() : "N.C.") . "</td>";
            $text .= "<td>" . (($inscrit->getAgent()->getUtilisateur()) ? $inscrit->getAgent()->getUtilisateur()->getEmail() : "N.C.") . "</td>";
            $text .= "</tr>";
        }
        $text .= "</tbody>";
        $text .= "</table>";

        return $text;
    }

    /** @noinspection PhpUnused */
    public function getDuree(bool $asNumber = false): string|float
    {
        $sum = DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00');
        /** @var Seance[] $journees */
        $journees = array_filter($this->journees->toArray(), function (Seance $a) {
            return $a->estNonHistorise();
        });
        foreach ($journees as $journee) {
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
                if ($volume) {
                    try {
                        $temp = new DateInterval('PT' . $volume . 'H');
                    } catch (Exception $e) {
                        throw new RuntimeException("Une erreur est survenue lors de la création de l'interval", 0, $e);
                    }
                    $sum->add($temp);
                }

            }
        }

        $result = $sum->diff(DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00'));
        if ($asNumber) {
            $heures = ($result->d * 24 + $result->h) + ($result->i) / 60;
            return $heures;
        } else {
            $heures = ($result->d * 24 + $result->h);
            $minutes = ($result->i);
            $text = $heures . " heures" . (($minutes !== 0) ? (" " . $minutes . " minutes") : "");
            return $text;
        }
    }

    /** @noinspection PhpUnused */
    public function getPlaceDisponible(string $liste): int
    {
        $inscriptions = array_filter(
            $this->getInscriptions(),
            function (Inscription $a) use ($liste) {
                return $a->estNonHistorise() and $a->getListe() === $liste;
            });
        return count($inscriptions);
    }

    public function isAnnee(string $annee): bool
    {
        $debut = DateTime::createFromFormat("d/m/Y H:i", "01/01/".$annee." 08:00");
        $fin = DateTime::createFromFormat("d/m/Y H:i", "31/12/".$annee." 18:00");

        foreach ($this->getSeances() as $seance) {
            if ($seance->getDateDebut() >= $debut AND $seance->getDateFin() <= $fin)  return true;
        }
        return false;
    }


}