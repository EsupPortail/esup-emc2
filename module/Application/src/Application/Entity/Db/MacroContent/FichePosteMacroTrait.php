<?php

namespace Application\Entity\Db\MacroContent;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use Element\Entity\Db\CompetenceType;
use Metier\Entity\Db\Reference;

trait FichePosteMacroTrait {

    /**
     * @return string
     */
    public function toStringFicheMetierPrincipal() : string
    {
        $ficheposte = $this;

        $texte  = "";
        $texte .= $ficheposte->getLibelleMetierPrincipal(FichePoste::TYPE_GENRE);
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringLibelleComplementaire() : string
    {
        $texte  = "";

        $ficheposte = $this;
        if ($ficheposte->getLibelle() !== null AND trim($ficheposte->getLibelle()) !== '') {
            $texte .= "<br/>" . trim($ficheposte->getLibelle());
        }

        return $texte;
    }

    /**
     * @return string
     */
    public function toStringCompositionFichesMetiers() : string
    {
        $ficheposte = $this;

        $quotiteTotal = 0;
        foreach ($ficheposte->getFichesMetiers() as $ficheTypeExterne) {
            $quotiteTotal += $ficheTypeExterne->getQuotite();
        }

        $texte  = $quotiteTotal. " %";
        $texte .= "<ul>";
        foreach ($ficheposte->getFichesMetiers() as $ficheTypeExterne) {
            $texte .= "<li>";
            $texte .= $ficheTypeExterne->getFicheType()->getMetier()->getLibelleGenre($ficheposte->getAgent());
//            foreach ($ficheTypeExterne->getFicheType()->getMetier()->getReferences() as $reference) {
//                $texte .= $reference->getUrl();
//            }
            $texte .= " (" .$ficheTypeExterne->getQuotite(). "%)";
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

        /**
     * @return string
     */
    public function toStringApplications() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $applications = $ficheposte->getDictionnaire('applications');
        $applications = array_filter($applications, function ($a) { return $a["conserve"] === true;});

        if (empty($applications)) return "";

        $result = [];
        foreach ($applications as $application) {
            $entite = $application["entite"];
            $result [$entite->getGroupe()?$entite->getGroupe()->getLibelle():"Sans groupe"][$entite->getId()] = $entite;
        }

        $texte = "<h3> Applications </h3>";
        $texte .= "<ul>";
        foreach ($result as $groupe => $liste) {
            $texte .= "<li class='rubrique'>";
            $texte .= $groupe;
            $texte .= "<ul>";
            foreach ($liste as $item) {
                $texte .= "<li class='element'>".$item->getLibelle()."</li>";
            }
            $texte .= "</ul>";
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @param int $typeId
     * @param bool $all
     * @return string
     */
    public function toStringCompetencesByTypes(int $typeId, bool $all=false) : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;

        $dictionnaire = $ficheposte->getDictionnaire('competences');
        if (!$all) $dictionnaire = array_filter($dictionnaire, function ($a) { return $a["conserve"] === true;});
        $dictionnaire = array_filter($dictionnaire, function ($a) use ($typeId) { return $a["entite"]->getType()->getId() === $typeId;});
        usort($dictionnaire, function ($a, $b) { return $a["entite"]->getLibelle() <=> $b["entite"]->getLibelle();});

        if (empty($dictionnaire)) return "";

        $competence = $dictionnaire[0]["entite"];
        $competenceType = "";
        switch($competence->getType()->getId()) {
            case 1 : $competenceType = "Compétences comportementales"; break;
            case 2 : $competenceType = "Compétences opérationnelles"; break;
            case 3 : $competenceType = "Connaissances"; break;
        }

        $texte = "<h3>".$competenceType."</h3>";
        $texte .= "<ul>";
        foreach ($dictionnaire as $element) {
            $texte .= "<li>";
            $texte .= $element["entite"]->getLibelle();
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringCompetencesConnaissances() : string
    {
        return $this->toStringCompetencesByTypes(CompetenceType::CODE_CONNAISSANCE);
    }

    /**
     * @return string
     */
    public function toStringAllCompetencesConnaissances() : string
    {
        return $this->toStringCompetencesByTypes(CompetenceType::CODE_CONNAISSANCE, true);
    }

    /**
     * @return string
     */
    public function toStringCompetencesOperationnelles() : string
    {
        return $this->toStringCompetencesByTypes(CompetenceType::CODE_OPERATIONNELLE);
    }

    /**
     * @return string
     */
    public function toStringAllCompetencesOperationnelles() : string
    {
        return $this->toStringCompetencesByTypes(CompetenceType::CODE_OPERATIONNELLE, true);
    }

    /**
     * @return string
     */
    public function toStringCompetencesComportementales() : string
    {
        return $this->toStringCompetencesByTypes(CompetenceType::CODE_COMPORTEMENTALE);
    }

    /**
     * @return string
     */
    public function toStringAllCompetencesComportementales() : string
    {
        return $this->toStringCompetencesByTypes(CompetenceType::CODE_COMPORTEMENTALE, true);
    }

    /**
     * @return string
     */
    public function toStringFichesMetiers() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $descriptionsRetirees = array_map(function (FicheposteActiviteDescriptionRetiree $a) { return $a->getActivite()->getId(); }, $ficheposte->getDescriptionsRetirees());

        $texte = "";
        foreach ($ficheposte->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $texte .= "<h3>";
            $texte .= $ficheMetier->getMetier()->getLibelleGenre($ficheposte->getAgent());
            $supplement = (($ficheTypeExterne->getPrincipale())?"Principal - ":"") . $ficheTypeExterne->getQuotite() . "%";
            $texte .= " (".$supplement.")";
            $references = $ficheMetier->getMetier()->getReferences();

            if ($references !== null AND !empty($references)) {
                $texte .= "<br/><small>";
                /** @var Reference $reference */
                foreach ($references as $reference) {
                    $texte .= " <a href='".$reference->getUrl()."'>".$reference->getReferentiel()->getLibelleCourt() . "-" . $reference->getCode()."</a>";
                }
                $texte.="</small>";
            }
            $texte .= "</h3>";

            $ids = explode(";",$ficheTypeExterne->getActivites());
            foreach ($ids as $id) {
                foreach ($ficheMetier->getMissions() as $activiteType) {
                    $mission = $activiteType->getMission();
                    if ($mission->getId() === (int) $id) {
                        $texte .= "<span class='activite-libelle'>". $mission->getLibelle() . "</span>";

                        $texte .= "<ul>";
                        foreach ($mission->getActivites() as $activite) {
                            if (!in_array($activite->getId(), $descriptionsRetirees)) {
                                $texte .= "<li>" . $activite->getLibelle() . "</li>";
                            }
                        }
                        $texte .= "</ul>";

                        break;
                    }
                }
            }
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringFichesMetiersCourt() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $descriptionsRetirees = array_map(function ($a) { return $a->getDescription()->getId(); }, $ficheposte->getDescriptionsRetirees()->toArray());

        $texte = "";
        foreach ($ficheposte->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $texte .= "<h3>";
            $texte .= $ficheMetier->getMetier()->getLibelleGenre($ficheposte->getAgent());
            $supplement = (($ficheTypeExterne->getPrincipale())?"Principal - ":"") . $ficheTypeExterne->getQuotite() . "%";
            $texte .= " (".$supplement.")";
            $references = $ficheMetier->getMetier()->getReferences();

            if ($references !== null AND !empty($references)) {
                $texte .= "<br/><small>";
                /** @var Reference $reference */
                foreach ($references as $reference) {
                    $texte .= " <a href='".$reference->getUrl()."'>".$reference->getReferentiel()->getLibelleCourt() . "-" . $reference->getCode()."</a>";
                }
                $texte.="</small>";
            }
            $texte .= "</h3>";

            $ids = explode(";",$ficheTypeExterne->getActivites());
            foreach ($ids as $id) {
                $texte .= "<ul>";
                foreach ($ficheMetier->getMissions() as $activiteType) {
                    $mission = $activiteType->getMission();

                    if ($mission->getId() === (int) $id) {

                        $texte .= "<li>";
                        $texte .= "<span class='activite-libelle'>". $mission->getLibelle() . "</span>";
                        $texte .= "</li>";

                        break;
                    }
                }
                $texte .= "</ul>";
            }
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringCadre() : string
    {
        $metier = null;

        /** @var FichePoste $ficheposte */
        $ficheposte = $this;

        foreach ($ficheposte->getFichesMetiers() as $ficheTypeExterne) {
            $tmp = $ficheTypeExterne->getFicheType()->getMetier();
            if ($metier === null OR $metier->getNiveau() < $tmp->getNiveau()) $metier = $tmp;
        }

        $texte = "";
        if ($metier === null) return $texte;
        if ($metier->getCategorie())            $texte .= "Catégorie : " . $metier->getCategorie()->getCode() . "<br/>";
        if (true /**$metier->getNiveau()**/)    $texte .= "Corps : " . "Lien manquant" . "<br/>";
        if (true /**$metier->getBap()**/)       $texte .= "Correspondance : " . "Lien manquant" . "<br/>";
        return $texte;
    }

    //Specificite ------------------------------------------------------------------------------------------------------


    /**
     * @return string
     */
    public function toStringSpecificiteComplete() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $specificite = $ficheposte->getSpecificite();

        $texte  = "";
        if ($specificite !== null) {
            if ($specificite->getSpecificite() !== null and trim($specificite->getSpecificite()) !== '') {
                $texte .= "<h3> Spécificités du poste </h3>";
                $texte .= trim($specificite->getSpecificite());
            }
            if ($specificite->getEncadrement() !== null and trim($specificite->getEncadrement()) !== '') {
                $texte .= "<h3> Encadrement </h3>";
                $texte .= trim($specificite->getEncadrement());
            }
            if ($specificite->getRelationsInternes() !== null and trim($specificite->getRelationsInternes()) !== '') {
                $texte .= "<h3> Relations internes à l'unicaen </h3>";
                $texte .= trim($specificite->getRelationsInternes());
            }
            if ($specificite->getRelationsExternes() !== null and trim($specificite->getRelationsExternes()) !== '') {
                $texte .= "<h3> Relations externes à l'unicaen </h3>";
                $texte .= trim($specificite->getRelationsExternes());
            }
            if ($specificite->getContraintes() !== null and trim($specificite->getContraintes()) !== '') {
                $texte .= "<h3> Sujétions ou conditions particulières </h3>";
                $texte .= trim($specificite->getContraintes());
            }
            if ($specificite->getMoyens() !== null and trim($specificite->getMoyens()) !== '') {
                $texte .= "<h3> Moyens et outils mis à disposition </h3>";
                $texte .= trim($specificite->getMoyens());
            }
            if ($specificite->getFormations() !== null and trim($specificite->getFormations()) !== '') {
                $texte .= "<h3> Formations et qualifications nécessaires </h3>";
                $texte .= trim($specificite->getFormations());
            }
        }
        if ($texte !== "") return $texte;
        return "Aucune spécificité décrite.";
    }

    /**
     * @return string
     */
    public function toStringSpecificiteSpecificite() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        if ($ficheposte->getSpecificite() === null) return '';
        $specificite = $ficheposte->getSpecificite()->getSpecificite();

        $texte = "";
        if ($specificite) {
            $texte .= "<div class='information'>";
            $texte .= "<h2>Spécificité du poste</h2>";
            $texte .=  $specificite;
            $texte .= "</div>";
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificiteEncadrement() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        if ($ficheposte->getSpecificite() === null) return '';
        $encadrement = $ficheposte->getSpecificite()->getEncadrement();

        $texte = "";
        if ($encadrement) {
            $texte .= "<div class='information'>";
            $texte .= "<h2>Encadrement</h2>";
            $texte .=  $encadrement;
            $texte .= "</div>";
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificiteRelations() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        if ($ficheposte->getSpecificite() === null) return '';
        $relationsInternes = $ficheposte->getSpecificite()->getRelationsInternes();
        $relationsExternes = $ficheposte->getSpecificite()->getRelationsExternes();

        $texte = "";
        if ($relationsInternes OR $relationsExternes) {
            $texte .= "<div class='information'>";
            $texte .= "<h2>Champs des relations</h2>";
            if ($relationsInternes) {
                $texte .= "<strong>Relations internes :</strong>" . $relationsInternes;
            }
            if ($relationsExternes) {
                $texte .= "<strong>Relations externes :</strong>" . $relationsExternes;
            }
            $texte .= "</div>";
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificiteContraintes() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        if ($ficheposte->getSpecificite() === null) return '';
        $contraintes = $ficheposte->getSpecificite()->getContraintes();

        $texte = "";
        if ($contraintes) {
            $texte .= "<div class='information'>";
            $texte .= "<h2>Conditions d'exercice</h2>";
            $texte .=  $contraintes;
            $texte .= "</div>";
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificiteMoyens() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        if ($ficheposte->getSpecificite() === null) return '';
        $moyens = $ficheposte->getSpecificite()->getMoyens();

        $texte = "";
        if ($moyens) {
            $texte .= "<div class='information'>";
            $texte .= "<h2>Moyens mis à disposition</h2>";
            $texte .=  $moyens;
            $texte .= "</div>";
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificiteFormations() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        if ($ficheposte->getSpecificite() === null) return '';
        $formations = $ficheposte->getSpecificite()->getFormations();

        $texte = "";
        if ($formations) {
            $texte .= "<div class='information'>";
            $texte .= "<h2>Formations et qualifications nécessaires</h2>";
            $texte .=  $formations;
            $texte .= "</div>";
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificiteActivites() : string
    {
        /** @var FichePoste $fiche */
        $fiche = $this;

        $activites = ($fiche->getSpecificite())?$fiche->getSpecificite()->getActivites():null;
        if ($activites === null or $activites === []) return "";


        $texte = "<h3> Autres missions spécifiques au poste </h3>";

        foreach ($activites as $activite) {
            $retrait = explode(";", $activite->getRetrait());
            $texte .= "<div>";
            $texte .= "<span class='activite'>".$activite->getActivite()->getCurrentActiviteLibelle()->getLibelle()."</span>";
            $texte .= "<ul>";
            foreach ($activite->getActivite()->getDescriptions() as $description) {
                if (!in_array($description->getId(), $retrait)) $texte .= "<li>". $description->getLibelle()."</li>";
            }
            $texte .= "</ul>";
            $texte .= "</div>";
        }
        return $texte;
    }
}