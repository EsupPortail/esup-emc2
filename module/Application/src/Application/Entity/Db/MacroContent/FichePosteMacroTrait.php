<?php

namespace Application\Entity\Db\MacroContent;

use Application\Entity\Db\CompetenceType;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\ParcoursDeFormation;
use Formation\Entity\Db\Formation;
use Metier\Entity\Db\Reference;

trait FichePosteMacroTrait {

    /**
     * @return string
     */
    public function toStringFicheMetierPrincipal() : string
    {
        /** @var FichePoste $ficheposte */
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

        /** @var FichePoste $ficheposte */
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
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;

        $texte  = "";
        $texte .= "<ul>";
        foreach ($ficheposte->getFichesMetiers() as $ficheTypeExterne) {
            $texte .= "<li>";
            $texte .= $ficheTypeExterne->getFicheType()->getMetier()->getLibelleGenre($ficheposte->getAgent());
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
        usort($dictionnaire, function ($a, $b) { return $a["entite"]->getLibelle() > $b["entite"]->getLibelle();});

        $texte = "<ul>";
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
    public function toStringFormations() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $formations = $ficheposte->getDictionnaire('formations');
        $formations = array_filter($formations, function ($a) { return $a["conserve"] === true;});

        if (empty($formations)) return "";

        $result = [];
        foreach ($formations as $formation) {
            /** @var Formation $entite */
            $entite = $formation["object"];
            $result [$entite->getGroupe()?$entite->getGroupe()->getLibelle():"Sans groupe"][$entite->getId()] = $entite;
        }
        ksort($result);
        $texte = "<h3> Formations </h3>";
        $texte .= "<ul>";
        foreach ($result as $groupe => $liste) {
            usort($liste, function (Formation $a, Formation $b) { return $a->getLibelle() > $b->getLibelle();});
            $texte .= "<li>";
            $texte .= $groupe;
            $texte .= "<ul>";
            foreach ($liste as $item) {
                $texte .= "<li>".$item->getLibelle()."</li>";
            }
            $texte .= "</ul>";
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringParcours() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $parcours = $ficheposte->getDictionnaire('parcours');
        ksort($parcours);
        $texte = "";

        foreach ($parcours as $clef => $parcoursArray) {
            foreach ($parcoursArray as $instance) {
                /** @var ParcoursDeFormation $instance */
                $texte .= "<h3>" . $instance->getLibelle() . "</h3>";

                /** Tri pour bonne affichage ******************************************************************************************/
                $nogroup = "ZZZZ";
                $formationArray = [];
                foreach ($instance->getFormations() as $formation) {
                    $groupe = ($formation->getFormation()->getGroupe())?$formation->getFormation()->getGroupe()->getLibelle():$nogroup;
                    $formationArray[$groupe][] = $formation;
                }

                usort($formationArray,
                    function($a, $b) {
                        $ordre_a = ($a[0]->getFormation()->getGroupe())?$a[0]->getFormation()->getGroupe()->getOrdre():9999;
                        $ordre_b = ($b[0]->getFormation()->getGroupe())?$b[0]->getFormation()->getGroupe()->getOrdre():9999;
                        return $ordre_a > $ordre_b;
                });

                foreach ($formationArray as $groupe => $formationSubarray) {
                    usort($formationSubarray, function ($a, $b) {
                        return $a->getOrdre() > $b->getOrdre();
                    });
                    $groupe = $formationSubarray[0]->getFormation()->getGroupe();

                    $texte .= "<ul>";
                    $texte .= "<li>";
                    $texte .= ($groupe !== null) ? $groupe->getLibelle() : "Sans groupe ...";
                    $texte .= "<ul>";
                    foreach($formationSubarray as $formationP) {
                        $texte .= "<li>". $formationP->getFormation()->getLibelle()."</li>";
                    }$texte .= "</ul>";
                    $texte .= "</li>";
                    $texte .= "</ul>";
                }
            }
        }
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringFichesMetiers() : string
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
                foreach ($ficheMetier->getActivites() as $activiteType) {
                    $activite = $activiteType->getActivite();
                    if ($activite->getId() === (int) $id) {
                        $texte .= "<span class='activite-libelle'>". $activite->getLibelle() . "</span>";

                        $texte .= "<ul>";
                        foreach ($activite->getDescriptions() as $description) {
                            if (array_search($description->getId(), $descriptionsRetirees) === false) {
                                $texte .= "<li>" . $description->getLibelle() . "</li>";
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
                foreach ($ficheMetier->getActivites() as $activiteType) {
                    $activite = $activiteType->getActivite();

                    if ($activite->getId() === (int) $id) {

                        $texte .= "<li>";
                        $texte .= "<span class='activite-libelle'>". $activite->getLibelle() . "</span>";
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
        if (true /**$metier->getBap()**/)       $texte .= "BAP : " . "Lien manquant" . "<br/>";
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

}