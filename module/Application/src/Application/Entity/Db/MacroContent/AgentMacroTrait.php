<?php

namespace Application\Entity\Db\MacroContent;

use Application\Entity\Db\Agent;

/**
 * Trait AgentMacroTrait
 */

trait AgentMacroTrait
{
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
        $temoins = [];
        $texte  = "<ul>";
        foreach ($statuts as $statut) {
            $texte .= "<li>";
            if ($statut->isTitulaire()) $temoins[] = "Titulaire";
            if ($statut->isCdi()) $temoins[] = "C.D.I.";
            if ($statut->isCdd()) $temoins[] = "C.D.D.";
            if (!empty($temoins)) {
                $texte .= implode(", ",$temoins);
                $texte .= " (";
                if($statut->getDateFin()) {
                    $texte .= "du " . $statut->getDateDebut()->format('d/m/Y') . " au " . $statut->getDateFin()->format('d/m/Y');
                } else {
                    $texte .= "depuis le " . $statut->getDateDebut()->format('d/m/Y');
                }
                $texte .= " " . $statut->getStructure()->getLibelleCourt() .")";
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
    public function toStringGradesActifs() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $grades = $agent->getGradesActifs();
        $texte  = "<ul>";
        foreach ($grades as $grade) {
            $texte .= "<li>";
            $texte .= "&nbsp;&nbsp;&nbsp;&nbsp;<span class='complement'>";
            $texte .= " BAP:" . $grade->getBap()->getCategorie();
            $texte .= " Corps:" . $grade->getCorps()->getLibelleLong();
            $texte .= "</span>";
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringQuotiteTravaillee() : string
    {
        /** @var Agent $agent */
        $agent = $this;
        $texte  = "";
        $texte .= ($agent->getQuotiteCourante())?$agent->getQuotiteCourante()->getQuotite()."%":"100%";
        return $texte;
    }
    /**
     * TODO a remplacer lorsque l'on aura les macros de macro
     * @return string
     */
    public function toStringAgentBloc() : string
    {
        $texte  = "";
        $texte .= "<table class='agent-bloc'>";
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
}