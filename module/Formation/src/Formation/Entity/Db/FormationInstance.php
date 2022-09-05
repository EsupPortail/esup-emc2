<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Formation\Provider\Etat\SessionEtats;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationInstance implements HistoriqueAwareInterface, HasSourceInterface, HasEtatInterface
{
    use HasEtatTrait;
    use HasSourceTrait;
    use HistoriqueAwareTrait;

    const TYPE_INTERNE = "formation interne";
    const TYPE_EXTERNE = "formation externe";
    const TYPE_REGIONALE = "formation régionale";

    /** @var integer */
    private $id;
    /** @var Formation */
    private $formation;
    /** @var string */
    private $complement;
    /** @var boolean */
    private $autoInscription;

    private int $nbPlacePrincipale = -1;
    private int $nbPlaceComplementaire = -1;
    /** @var string */
    private $lieu;
    /** @var string */
    private $type;
    private ?float $coutHt  = null;
    private ?float $coutTtc = null;
    private bool $affichage = true;

    /** @var ArrayCollection (FormationInstanceJournee) */
    private $journees;
    /** @var ArrayCollection (FormationInstanceInscrit) */
    private $inscrits;
    /** @var ArrayCollection (Formateur) */
    private $formateurs;



    /**
     * @return string
     */
    public function generateTag()  : string
    {
        return 'FormationInstance_' . $this->getId();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return Formation
     */
    public function getFormation() : Formation
    {
        return $this->formation;
    }

    /**
     * @param Formation|null $formation
     * @return FormationInstance
     */
    public function setFormation(?Formation $formation) : FormationInstance
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplement() : ?string
    {
        return $this->complement;
    }

    /**
     * @param string|null $complement
     * @return FormationInstance
     */
    public function setComplement(?string $complement) : FormationInstance
    {
        $this->complement = $complement;
        return $this;
    }

    /** PLACE SUR LISTE  **********************************************************************************************/

    /**
     * @return int
     */
    public function getNbPlacePrincipale() : int
    {
        return $this->nbPlacePrincipale;
    }

    /**
     * @param int $nbPlacePrincipale
     * @return FormationInstance
     */
    public function setNbPlacePrincipale(int $nbPlacePrincipale) : FormationInstance
    {
        $this->nbPlacePrincipale = $nbPlacePrincipale;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbPlaceComplementaire() : int
    {
        return $this->nbPlaceComplementaire;
    }

    /**
     * @param int $nbPlaceComplementaire
     * @return FormationInstance
     */
    public function setNbPlaceComplementaire(int $nbPlaceComplementaire) : FormationInstance
    {
        $this->nbPlaceComplementaire = $nbPlaceComplementaire;
        return $this;
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
     * @return FormationInstance
     */
    public function setLieu(?string $lieu): FormationInstance
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
     * @return FormationInstance
     */
    public function setType(?string $type): FormationInstance
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoInscription(): bool
    {
        return ($this->autoInscription)?:false;
    }

    /**
     * @param bool $autoInscription
     * @return FormationInstance
     */
    public function setAutoInscription(bool $autoInscription = false): FormationInstance
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
    public function getFormateurs(): ?array
    {
        if ($this->formateurs === null) return null;
        return $this->formateurs->toArray();
    }

    /** JOURNEE *******************************************************************************************************/

    /**
     * @return array|null
     */
    public function getJournees() : ?array
    {
        if ($this->journees === null) return null;
        return $this->journees->toArray();
    }

    /**
     * @return string|null
     */
    public function getDebut() : ?string
    {
        $minimum = null;
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise() AND $journee->getType() === Seance::TYPE_SEANCE) {
                $split = explode("/", $journee->getJour()->format('d/m/Y'));
                $reversed = $split[2] . "/" . $split[1] . "/" . $split[0];
                if ($minimum === null or $reversed < $minimum) $minimum = $reversed;
            }
        }
        if ($minimum !== null) {
            $split = explode("/", $minimum);
            $minimum = $split[2] . "/" . $split[1] . "/" . $split[0];
        }
        return $minimum;
    }

    /**
     * @return string|null
     */
    public function getFin() : ?string
    {
        $maximum = null;
        /** @var Seance $journee */
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise() AND $journee->getType() === Seance::TYPE_SEANCE) {
                $split = explode("/", $journee->getJour()->format('d/m/Y'));
                $reversed = $split[2] . "/" . $split[1] . "/" . $split[0];
                if ($maximum === null or $reversed > $maximum) $maximum = $reversed;
            }
        }
        if ($maximum !== null) {
            $split = explode("/", $maximum);
            $maximum = $split[2] . "/" . $split[1] . "/" . $split[0];
        }
        return $maximum;
    }

    /**
     * @return bool
     */
    public function hasJournee() : bool
    {
        /** @var Seance $journee */
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise()) return true;
        }
        return false;
    }
    /** INSCRIT *******************************************************************************************************/

    /**
     * @return FormationInstanceInscrit[]|null
     */
    public function getInscrits() : ?array
    {
        if ($this->inscrits === null) return null;
        return $this->inscrits->toArray();
    }

    /**
     * @return FormationInstanceInscrit[]|null
     */
    public function getListePrincipale() : ?array
    {
        if ($this->inscrits === null) return null;
        $array = array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) {
            return ($a->getListe() === FormationInstanceInscrit::PRINCIPALE AND $a->estNonHistorise());
        });
        usort($array, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) {
            return $a->getAgent()->getDenomination() > $b->getAgent()->getDenomination();
        });
        return $array;
    }

    /**
     * @return FormationInstanceInscrit[]|null
     */
    public function getListeComplementaire() : ?array
    {
        if ($this->inscrits === null) return null;
        $array = array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) {
            return ($a->getListe() === FormationInstanceInscrit::COMPLEMENTAIRE AND $a->estNonHistorise());
        });
        usort($array, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) {
            return $a->getHistoCreation() > $b->getHistoCreation();
        });
        return $array;
    }

    /**
     * @return FormationInstanceInscrit[]|null
     */
    public function getListeHistorisee() : ?array
    {
        if ($this->inscrits === null) return null;
        $array = array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) {
            return $a->estHistorise();
        });
        usort($array, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) {
            return $a->getAgent()->getDenomination() > $b->getAgent()->getDenomination();
        });
        return $array;
    }

    /**
     * @return bool
     */
    public function isListePrincipaleComplete() : bool
    {
        if ($this->inscrits === null) return false;
        $array = array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) {
            return $a->getListe() === FormationInstanceInscrit::PRINCIPALE;
        });
        return (count($array) >= $this->getNbPlacePrincipale());
    }

    /**
     * @return bool
     */
    public function isListeComplementaireComplete() : bool
    {
        if ($this->inscrits === null) return false;
        $array = array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) {
            return $a->getListe() === FormationInstanceInscrit::COMPLEMENTAIRE;
        });
        return (count($array) >= $this->getNbPlaceComplementaire());
    }

    /**
     * @return string|null
     */
    public function getListeDisponible() : ?string
    {
        $liste = null;
        if ($liste === null and !$this->isListePrincipaleComplete()) $liste = FormationInstanceInscrit::PRINCIPALE;
        if ($liste === null and !$this->isListeComplementaireComplete()) $liste = FormationInstanceInscrit::COMPLEMENTAIRE;
        return $liste;
    }

    /**
     * @param Agent $agent
     * @return bool
     */
    public function hasAgent(Agent $agent) :  bool
    {
        foreach ($this->inscrits as $inscrit) {
            if ($inscrit->getAgent() === $agent) return true;
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

    /** PREDICAT D'ETAT *********************************************************************************************/

    public function estPreparation() : bool
    {
        return (
            $this->getEtat()->getCode() === SessionEtats::ETAT_CREATION_EN_COURS OR
            $this->getEtat()->getCode() === SessionEtats::ETAT_INSCRIPTION_OUVERTE OR
            $this->getEtat()->getCode() === SessionEtats::ETAT_INSCRIPTION_FERMEE
        );
    }

    public function estPrete() : bool
    {
        return (
            $this->getEtat()->getCode() === SessionEtats::ETAT_FORMATION_CONVOCATION
        );
    }

    public function estRealisee() : bool
    {
        return (
            $this->getEtat()->getCode() === SessionEtats::ETAT_ATTENTE_RETOURS OR
            $this->getEtat()->getCode() === SessionEtats::ETAT_CLOTURE_INSTANCE
        );
    }

    /** Fonctions pour les macros **********************************************************************************/

    public function getInstanceLibelle() : string
    {
        return $this->getFormation()->getLibelle();
    }

    public function getInstanceCode() : string
    {
        return $this->getFormation()->getId() . "/" . $this->getId();
    }

    public function getListeFormateurs() : string
    {
        /** @var Formateur[] $formateurs */
        $formateurs = $this->getFormateurs();
        usort($formateurs, function (Formateur $a, Formateur $b) {
            return ($a->getNom() . " " . $a->getPrenom()) > ($b->getNom() . " " . $b->getPrenom());
        });

        $text  = "<table style='width:100%;'>";
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

    public function getListeJournees() : string
    {
        /** @var Seance[] $journees */
        $journees = $this->getJournees();
        //usort($journees, function (FormationInstanceJournee $a, FormationInstanceJournee $b) { return $a > $b;});

        $text  = "<table style='width:100%;'>";
        $text .= "<thead>";
        $text .= "<tr style='border-bottom:1px solid black;'>";
        $text .= "<th>Date  </th>";
        $text .= "<th>de  </th>";
        $text .= "<th>à  </th>";
        $text .= "<th>Lieu  </th>";
        $text .= "</tr>";
        $text .= "</thead>";
        $text .= "<tbody>";
        foreach ($journees as $journee) {
            $text .= "<tr>";
                if ($journee->getType() === Seance::TYPE_SEANCE) {
                    $text .= "<td>" . $journee->getJour()->format('d/m/Y') . "</td>";
                    $text .= "<td>" . $journee->getDebut() . "</td>";
                    $text .= "<td>" . $journee->getFin() . "</td>";
                }
                if ($journee->getType() === Seance::TYPE_VOLUME) {
                    $text .="<td colspan='2'>Volume horaire</td>";
                    $text .="<td>" .$journee->getVolume(). " heures </td>";
                }
            $text .= "<td>" . $journee->getLieu() . "</td>";
            $text .= "</tr>";
        }
        $text .= "</tbody>";
        $text .= "</table>";

        return $text;
    }

    public function getPeriode() : string
    {
        return $this->getDebut() . " au " . $this->getFin();

    }

    public function getListeComplementaireAgents() : string
    {
        /** @var Seance[] $inscrits */
        $inscrits = $this->getListeComplementaire();
        $inscrits = array_filter($inscrits, function (FormationInstanceInscrit $a) {
            return $a->estNonHistorise();
        });
        usort($inscrits, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) {
            return ($a->getAgent()->getNomUsuel() . " " . $a->getAgent()->getPrenom()) > ($b->getAgent()->getNomUsuel() . " " . $b->getAgent()->getPrenom());
        });

        $text  = "<table style='width:100%;'>";
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

    public function getDuree() : string
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
                $duree = $debut->diff($fin);
                $sum->add($duree);
            }
            if ($journee->getType() === Seance::TYPE_VOLUME) {
                $volume = $journee->getVolume();
                $temp = new DateInterval('PT'.$volume.'H');
                $sum->add($temp);
            }
        }

        $result = $sum->diff(DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00'));
        $heures = ($result->d * 24 + $result->h);
        $minutes = ($result->i);
        $text = $heures . " heures" . (($minutes !== 0) ? (" ".$minutes . " minutes") : "");
        return $text;
    }

    public function getPlaceDisponible(string $liste) : int
    {
        $inscriptions = array_filter(
            $this->getInscrits(),
            function(FormationInstanceInscrit $a)  use ($liste) { return $a->estNonHistorise() AND $a->getListe() === $liste;
        });
        return count($inscriptions);
    }
}