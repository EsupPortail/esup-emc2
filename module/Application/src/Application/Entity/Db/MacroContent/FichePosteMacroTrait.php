<?php

namespace Application\Entity\Db\MacroContent;

use Application\Entity\Db\Competence;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\ParcoursDeFormation;
use Formation\Entity\Db\Formation;

trait FichePosteMacroTrait {

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
            $texte .= $ficheTypeExterne->getFicheType()->getMetier()->getLibelle();
            $texte .= " (" .$ficheTypeExterne->getQuotite(). "%)";
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function toStringSpecificite() : string
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
     * @return string
     */
    public function toStringCompetences() : string
    {
        /** @var FichePoste $ficheposte */
        $ficheposte = $this;
        $competences = $ficheposte->getDictionnaire('competences');
        $competences = array_filter($competences, function ($a) { return $a["conserve"] === true;});

        if (empty($competences)) return "";

        $result = [];
        foreach ($competences as $competence) {
            /** @var Competence $entite */
            $entite = $competence["object"];
            $result [$entite->getType()?$entite->getType()->getLibelle():"Sans type"][$entite->getId()] = $entite;
        }
        ksort($result);
        $texte = "<h3> Compétences </h3>";
        $texte .= "<ul>";
        foreach ($result as $groupe => $liste) {
            usort($liste, function (Competence $a, Competence $b) { return $a->getLibelle() > $b->getLibelle();});
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
            $texte .= "<h3>" . $ficheMetier->getMetier()->getLibelle() . "</h3>";

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
}