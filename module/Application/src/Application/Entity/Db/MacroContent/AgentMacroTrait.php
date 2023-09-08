<?php

namespace Application\Entity\Db\MacroContent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentEchelon;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\AgentQuotite;

/**
 * Trait AgentMacroTrait
 */

trait AgentMacroTrait
{

    public function toStringPrenom() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        if ($agent->getPrenom()) return $agent->getPrenom();
        return "Aucun prénom de renseigné";
    }

    public function toStringNomUsage() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        if ($agent->getNomUsuel()) return $agent->getNomUsuel();
        return "Aucun nom d'usage de renseigné";
    }

    public function toStringNomFamille() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        if ($agent->getNomFamille()) return $agent->getNomFamille();
        return "Aucun nom de famille de renseigné";
    }

    /**
     * @return string
     */
    public function toStringDenomination(): string
    {
        /** @var Agent $agent */
        $agent = $this;
        $nomUsuel = $agent->getNomUsuel();
        $prenom = $agent->getPrenom();
        return $prenom . " " . $nomUsuel;
    }

    /**
     * @return string
     */
    public function toStringAffectationsActives() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $affecations = $agent->getAffectationsActifs();
        $texte  = "<ul>";
        foreach ($affecations as $affectation) {
            $texte .= "<li>";
            $texte .= $affectation->getStructure()->toStringDenomination();
            $texte .= " (";
            if($affectation->getDateFin()) {
                $texte .= "du " . $affectation->getDateDebut()->format('d/m/Y') . " au " . $affectation->getDateFin()->format('d/m/Y');
            } else {
                $texte .= "depuis le " . $affectation->getDateDebut()->format('d/m/Y');
            }
            $texte .= ")";
            if ($affectation->isPrincipale()) {
                $texte .= "<br/>";
                $texte .= "&nbsp;&nbsp;&nbsp;&nbsp;<span class='complement'>";
                $texte .= "Affectation principale";
                $texte .= "</span>";
            }
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringStatutsActifs() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $statuts = $agent->getStatutsActifs();
//        $texte  = "<ul>";
        foreach ($statuts as $statut) {
            $temoins = [];
            if ($statut->isTitulaire()) $temoins[] = "Titulaire";
            if ($statut->isCdi()) $temoins[] = "C.D.I.";
            if ($statut->isCdd()) $temoins[] = "C.D.D.";
            if (!empty($temoins)) {
//                $texte .= "<li>";
                $texte .= implode(", ",$temoins);
//                $texte .= " (";
//                if($statut->getDateFin()) {
//                    $texte .= "du " . $statut->getDateDebut()->format('d/m/Y') . " au " . $statut->getDateFin()->format('d/m/Y');
//                } else {
//                    $texte .= "depuis le " . $statut->getDateDebut()->format('d/m/Y');
//                }
//                $texte .= " - " . $statut->getStructure()->getLibelleCourt() .")";
                //$texte .= "</span>";
                $texte .= "<br/>";
            }
        }
//        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringGradesActifs() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $grades = $agent->getGradesActifs();
//        $texte  = "<ul>";
        $texte  = "";
        foreach ($grades as $grade) {
//            $texte .= "<li>";
            $grade_libelle = $grade->getCorps()->getLibelleLong();
            $correspondance = $grade->getCorrespondance();
            if ($correspondance) {
                $grade_bap = $correspondance->getType()->getLibelleCourt(). " " .$correspondance->getLibelleCourt();
            } else {
                $grade_bap = "";
            }
            $texte .= $grade_libelle . " ". $grade_bap;
//            $texte .= " (";
//            if($grade->estFini()) {
//                $texte .= "du " . $grade->getDateDebut()->format('d/m/Y') . " au " . $grade->getDateFin()->format('d/m/Y');
//            } else {
//                $texte .= "depuis le " . (($grade->getDateDebut() !== null)?$grade->getDateDebut()->format('d/m/Y'):"---");
//            }
//            $texte .= " - ";
//            $texte .= $grade->getStructure()->getLibelleCourt() .")";
//            $texte .= "</span>";
//            $texte .= "</li>";
            $texte .= "<BR>";
        }
//        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringQuotiteTravaillee() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $quotites = $agent->getQuotitesActives();
        if ($quotites === null or empty($quotites)) return 'Aucune quotité de remontée du SIRH';

        $texte = "";
        $texte = implode("<br>",array_map(function (AgentQuotite $a) { return ($a->getQuotite())?$a->getQuotite()."%":"100%"; }, $quotites));
        return $texte;
    }

    public function toStringQuotiteAffectation() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $affectations = $agent->getAffectationsActifs();
        if (empty($affectations)) return "Aucune quotité  d'affectation de remontée du SIRH";

        $texte = "<ul>";
        foreach ($affectations as $affectation) {
            $texte .= "<li>";
            $texte .= $affectation->getStructure()->getLibelleLong() . " ";
            if ($affectation->isHierarchique()) $texte .= " Hiérarchique ";
            if ($affectation->isFonctionnelle()) $texte .= " Fonctionnelle ";
            if ($affectation->getQuotite()) {
                $texte .= $affectation->getQuotite() . "%";
            } else {
                $texte .= "100%";
            }

            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * TODO a remplacer lorsque l'on aura les macros de macro
     * @return string
     */
    public function toStringAgentBloc() : string
    {
        $texte  = "<table class='agent-bloc'>";
        $texte .= "<tr>";
        $texte .= "<th>Dénomination</th>";
        $texte .= "<td>".$this->toStringDenomination()."</td>";
        $texte .= "</tr>";
        $texte .= "<tr>";
        $texte .= "<th> Affectation(s) </th>";
        $texte .= "<td>".$this->toStringAffectationsActives()."</td>";
        $texte .= "</tr>";
        $texte .= "<tr>";
        $texte .= "<th> Statut(s) </th>";
        $texte .= "<td>".$this->toStringStatutsActifs()."</td>";
        $texte .= "</tr>";
        $texte .= "<tr>";
        $texte .= "<th> Grade(s) </th>";
        $texte .= "<td>".$this->toStringGradesActifs()."</td>";
        $texte .= "</tr>";
        $texte .= "<tr>";
        $texte .= "<th> Quotité travaillée </th>";
        $texte .= "<td>".$this->toStringQuotiteTravaillee()."</td>";
        $texte .= "</tr>";
        $texte .= "</table>";
        return $texte;
    }

    /**
     * TODO a remplacer lorsque l'on aura les macros de macro
     * @return string
     */
    public function toStringStructuresBloc() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $structures = $agent->getStructures();

        $texte = "";
        foreach ($structures as $structure) {
            if ($texte !== "") $texte .= "<br/>";
            $texte .= $structure->toStringStructureBloc();
        }
        return $texte;
    }

    public function toStringDateNaissance() : string
    {
        /** @var Agent $agent */
        $agent = $this;

        if ($agent->getDateNaissance()) {
            return $agent->getDateNaissance()->format('d/m/Y');
        }
        return "Date de naissance non renseignée";
    }

    public function toStringAffectationStructure() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $structure = ($agent->getAffectationPrincipale())?$agent->getAffectationPrincipale()->getStructure():null;

        if ($structure === null) return "Aucune Structure";
        if ($structure->getNiv2() === null or $structure === $structure->getNiv2()) return $structure->getLibelleLong();

        return $structure->getNiv2()->getLibelleLong();
    }

    public function toStringAffectationStructureFine() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $structure = ($agent->getAffectationPrincipale())?$agent->getAffectationPrincipale()->getStructure():null;

        if ($structure === null) return "Aucune Structure";
        return $structure->getLibelleLong();
    }

    public function toStringAffectationDate() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $date = ($agent->getAffectationPrincipale())?$agent->getAffectationPrincipale()->getDateDebut():null;

        if ($date === null) return "Aucune date";

        return $date->format('d/m/Y');
    }

    public function toStringCorpsGrade() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $grades = ($agent->getGradesActifs())?$agent->getGradesActifs():null;

        if ($grades === null) return "Aucune date";

        $texte = "";
        foreach ($grades as $grade) {
            $texte .= $grade->getCorps()->getLibelleLong() . "  - " . $grade->getGrade()->getLibelleLong();
        }
        return $texte;
    }

    public function toStringIntitulePoste() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $fiche = $agent->getFichePosteBest();

        if ($fiche === null) return "Aucune fiche de poste EMC2";
        $metier  = $fiche->getLibelleMetierPrincipal();
        $complement = $fiche->getLibelle();

        if ($complement) return $complement . " rattaché à " . $metier;
        if ($metier === null) return "Aucun metier principal dans la fiche de poste";
        return $metier;
    }

    public function toStringMissions() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $fiche = $agent->getFichePosteBest();

        if ($fiche === null) return 'Aucune fiche de poste EMC2';
        if ($fiche->getFicheTypeExternePrincipale() === null) return 'Aucune fiche de métier EMC2 principale';
       $missions = $fiche->getFicheTypeExternePrincipale()->getFicheType()->getMissions();

        $texte = "<ul>";
        foreach ($missions as $mission) {
            $texte .= "<li>" . $mission->getMission()->getLibelle() . "</li>";
        }
        $texte .= "</ul>";

        return $texte;
    }

    public function toStringEmploiType() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $fiche = $agent->getFichePosteBest();

        //todo /!\ overlap avec les champs manuels du CREP
        if ($fiche === null) return "Aucune fiche de poste EMC2";
        if ($fiche->getFicheTypeExternePrincipale() === null) return "";
        $metier  = $fiche->getFicheTypeExternePrincipale()->getFicheType()->getMetier()->getReferencesAffichage();

        return $metier;
    }

    public function toStringQuotite() : string
    {
        return $this->toStringQuotiteTravaillee();
    }

    public function toStringEchelon() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $echelons = $agent->getEchelonsActifs();

        $texte = implode("<br>", array_map(function (AgentEchelon $a) { return $a->getEchelon();}, $echelons));
        return $texte;
    }

    public function toStringEchelonPassage() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $echelons = $agent->getEchelonsActifs();

        $texte = implode("<br>", array_map(function (AgentEchelon $a) { return $a->getDateDebutToString();}, $echelons));
        return $texte;
    }

    public function toStringMissionsSpecifiques() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $missions = array_filter($agent->getMissionsSpecifiques(), function (AgentMissionSpecifique $a) { return $a->estEnCours() AND $a->estNonHistorise(); });

        if (empty($missions)) {
            return "";
        } else {
            $texte  = "<h2>Missions spécifiques de l'agent</h2>";
            $texte .= "<ul>";
            foreach ($missions as $mission) {
                $texte .= "<li>";
                $texte .= $mission->getMission()->getLibelle();
                if ($mission->getStructure()) {
                    $texte .= " - " . $mission->getStructure()->getLibelleLong();
                    if ($mission->getStructure()->getNiv2() !== null AND $mission->getStructure()->getNiv2() !== $mission->getStructure()) {
                        $texte .= " (" . $mission->getStructure()->getNiv2()->getLibelleLong() . ")";
                    }
                }
                $texte .= "</li>";
            }
            $texte .= "</ul>";
        }
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getDateNaissanceAsString() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        if ($agent->getDateNaissance() === null) return "Date de naissance non renseignée";
        return $agent->getDateNaissance()->format('d/m/y');
    }
}