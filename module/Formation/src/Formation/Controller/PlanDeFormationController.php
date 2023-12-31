<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\Axe;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\PlanDeFormation;
use Formation\Form\PlanDeFormation\PlanDeFormationFormAwareTrait;
use Formation\Form\PlanDeFormationImportation\PlanDeFormationImportationFormAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationFormAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Axe\AxeServiceAwareTrait;
use Formation\Service\Domaine\DomaineServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PlanDeFormationController extends AbstractActionController
{
    use AxeServiceAwareTrait;
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;

    use PlanDeFormationFormAwareTrait;
    use PlanDeFormationImportationFormAwareTrait;
    use SelectionFormationFormAwareTrait;
    use SelectionPlanDeFormationFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $plans = $this->getPlanDeFormationService()->getPlansDeFormation();
        return new ViewModel([
            'plans' => $plans,
        ]);
    }

    public function courantAction(): ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getPlanDeFormationByAnnee();

        if (empty($planDeFormation)) {
            return new ViewModel();
        }

        $formations = $planDeFormation->getFormations();
        $sansgroupe = new FormationGroupe();
        $sansgroupe->setLibelle("Formations sans groupe");
        $sansaxe = new Axe();
        $sansaxe->setLibelle("Thème de formation sans axe");

        $groupes = [];
        $formationsArrayByGroupe = [];
        $groupesArrayByAxe = [];
        foreach ($formations as $formation) {
            $groupe = $formation->getGroupe();
            if ($groupe !== null) {
                $groupes[$formation->getGroupe()->getId()] = $groupe;
                $formationsArrayByGroupe[$groupe->getId()][] = $formation;
                $axe = $groupe->getAxe();
                if ($axe !== null) {
                    $axes[$axe->getId()] = $axe;
                    $groupesArrayByAxe[$axe->getId()][$groupe->getId()] = $groupe;
                }
            } else {
                $axes[-1] = $sansaxe;
                $groupes[-1] = $sansgroupe;
                $formationsArrayByGroupe[-1][] = $formation;
                $groupesArrayByAxe[-1][-1] = $sansgroupe;
            }
        }

        $sessionsArrayByFormation = [];
        foreach ($formations as $formation) {
            //todo recupérer par lot
            $sessionsArrayByFormation[$formation->getId()] = $this->getFormationInstanceService()->getFormationsInstancesOuvertesByFormation($formation);
        }

        $abonnements = [];
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent !== null) $abonnements = $this->getAbonnementService()->getAbonnementsByAgent($agent);

        return new ViewModel([
            'planDeFormation' => $planDeFormation,
            'formations' => $formations,
            'groupes' => $groupes,
            'axes' => $axes,
            'groupesArrayByAxe' => $groupesArrayByAxe,
            'formationsArrayByGroupe' => $formationsArrayByGroupe,
            'sessionsArrayByFormation' => $sessionsArrayByFormation,
            'abonnements' => $abonnements,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);
        $formations = $plan->getFormations();

        $axes = [];

        $sansgroupe = new FormationGroupe();
        $sansgroupe->setLibelle("Formations sans thème");
        $sansaxe = new Axe();
        $sansaxe->setLibelle("Thème de formation sans axe");

        $groupes = [];
        $formationsArrayByGroupe = [];
        $groupesArrayByAxe = [];
        foreach ($formations as $formation) {
            $groupe = $formation->getGroupe();
            if ($groupe !== null) {
                $groupes[$formation->getGroupe()->getId()] = $groupe;
                $formationsArrayByGroupe[$groupe->getId()][] = $formation;
                $axe = $groupe->getAxe();
                if ($axe !== null) {
                    $axes[$axe->getId()] = $axe;
                    $groupesArrayByAxe[$axe->getId()][$groupe->getId()] = $groupe;
                }
            } else {
                $axes[-1] = $sansaxe;
                $groupes[-1] = $sansgroupe;
                $formationsArrayByGroupe[-1][] = $formation;
                $groupesArrayByAxe[-1][-1] = $sansgroupe;
            }
        }

        $sessionsArrayByFormation = [];
        foreach ($formations as $formation) {
            //todo recupérer par lot
            $sessionsArrayByFormation[$formation->getId()] = $this->getFormationInstanceService()->getFormationsInstancesByFormationAndPlan($formation, $plan);
        }

        return new ViewModel([
            'plan' => $plan,
            'formations' => $formations,
            'groupes' => $groupes,
            'axes' => $axes,
            'groupesArrayByAxe' => $groupesArrayByAxe,
            'formationsArrayByGroupe' => $formationsArrayByGroupe,
            'sessionsArrayByFormation' => $sessionsArrayByFormation,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $plan = new PlanDeFormation();

        $form = $this->getPlanDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/ajouter', [], [], true));
        $form->bind($plan);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPlanDeFormationService()->create($plan);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter un plan de formation",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $form = $this->getPlanDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/modifier', ['plan-de-formation' => $plan->getId()], [], true));
        $form->bind($plan);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPlanDeFormationService()->update($plan);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier le plan de formation",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function supprimerAction(): ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getPlanDeFormationService()->delete($plan);
            exit();
        }

        $vm = new ViewModel();
        if ($plan !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du plan de formation " . $plan->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('plan-de-formation/supprimer', ["plan-de-formation" => $plan->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des formations d'un plan de formation **************************************************************/

    public function gererFormationsAction() : ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/gerer-formations', ['plan-de-formation' => $planDeFormation->getId()], [], true));
        $form->bind($planDeFormation);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formationIds = $data['formations'];

            foreach ($planDeFormation->getFormations() as $formation) {
                if (!in_array($formation->getId(), $formationIds)) {
                    $formation->removePlanDeFormation($planDeFormation);
                    $this->getFormationService()->update($formation);
                }
            }
            foreach ($formationIds as $formationId) {
                $formation = $this->getFormationService()->getFormation($formationId);
                if (!$formation->hasPlanDeFormation($planDeFormation)) {
                    $formation->addPlanDeForamtion($planDeFormation);
                    $this->getFormationService()->update($formation);
                }
            }

            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajouter une formation au plan de formation [".$planDeFormation->getAnnee()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    /** Reprend les formations d'un plan de formation et les ajoute à plan courant */
    public function reprendreAction() : ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $form = $this->getSelectionPlanDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/reprendre', ['plan-de-formation' => $planDeFormation->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $toRecopy = $this->getPlanDeFormationService()->getPlanDeFormation($data['plan']);
            if ($toRecopy !== null) {
                $this->getPlanDeFormationService()->transferer($toRecopy, $planDeFormation);
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Reprendre un plan de formation pour le plan de formation [".$planDeFormation->getAnnee()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function viderAction() : ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getPlanDeFormationService()->vider($plan);
            exit();
        }

        $vm = new ViewModel();
        if ($plan !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Retrait des formations du plan de formation " . $plan->getAnnee(),
                'text' => "Cette opération est sans retour en arrière. Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('plan-de-formation/vider', ["plan-de-formation" => $plan->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function importerDepuisCsvAction(): ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);
        if ($plan === null) $plan = new PlanDeFormation();

        $form = $this->getPlanDeFormationImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/importer', ['mode' => 'preview', 'path' => null], [], true));
        $form->bind($plan);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];
            $plan = $this->getPlanDeFormationService()->getPlanDeFormation($data['plan-de-formation']);




            $nouveauxAxes = [];
            $nouveauxThemes = [];
            $nouveauxFormations = [];
            $nouveauxDomaines = [];

            //reading
            $array = [];
            if ($plan === null) {
                $error[] = "Aucun plan de formation !";
            }
            if ($fichier_path === null or $fichier_path === '') {
                $error[] = "Aucun fichier !";
            } else {
                $handle = fopen($fichier_path, "r");

                while ($content = fgetcsv($handle, 0, ";")) {
                    $array[] = $content;
                }

                $header = $array[0];
                //TODO detecter column
                $axeColumn = array_search("Axe", $header);
                $themeColumn = array_search("Theme", $header);
                $formationColumn = array_search("Action", $header);
                $domaineColumn = array_search("Domaine", $header);
                $descriptionColumn = array_search("Description", $header);
                $objectifColumn = array_search("Objectif", $header);
                $programmeColumn = array_search("Programme", $header);
                $modaliteColumn = array_search("Modalite", $header);

                if ($axeColumn === false) $error[] = "La colonne [Axe] n'a pas été trouvée dans le fichier csv !";
                if ($themeColumn === false) $error[] = "La colonne [Theme] n'a pas été trouvée dans le fichier csv !";
                if ($formationColumn === false) $error[] = "La colonne [Action] n'a pas été trouvée dans le fichier csv !";
                if ($domaineColumn === false) $error[] = "La colonne [Domaine] n'a pas été trouvée dans le fichier csv !";
                if ($descriptionColumn === false) $error[] = "La colonne [Description] n'a pas été trouvée dans le fichier csv !";
                if ($objectifColumn === false) $error[] = "La colonne [Objectif] n'a pas été trouvée dans le fichier csv !";
                if ($programmeColumn === false) $error[] = "La colonne [Programme] n'a pas été trouvée dans le fichier csv !";
                if ($modaliteColumn === false) $error[] = "La colonne [Modalite] n'a pas été trouvée dans le fichier csv !";

                $array = array_slice($array, 1);
                $axes = [];
                $themes = [];
                $formations = [];
                $domaines = [];

                //Lecture
                foreach ($array as $line) {
                    $axeLibelle = trim($line[$axeColumn]);
                    $axe = ($axeLibelle !== '') ? $this->getAxeService()->getAxeByLibelle($axeLibelle) : null;
                    if ($axe === null && $axeLibelle !== '') {
                        $nouveauxAxes[$axeLibelle] = $axeLibelle;
                    } else {
                        $axes[$axeLibelle] = $axe;
                    }

                    $themeLibelle = trim($line[$themeColumn]);
                    $theme = ($themeLibelle !== '') ? $this->getFormationGroupeService()->getFormationGroupeByLibelle($themeLibelle, $axe) : null;
                    if ($theme === null && $themeLibelle !== '') {
                        $nouveauxThemes[$themeLibelle] = $themeLibelle;
                    } else {
                        $themes[$themeLibelle] = $theme;
                    }

                    $formationLibelle = $line[$formationColumn];
                    if ($formationLibelle !== '') {
                        $formation = $this->getFormationService()->getFormationByLibelle($formationLibelle, $theme);
                        if ($formation === null) {
                            $nouveauxFormations[$formationLibelle] = $formationLibelle;
                        } else {
                            $formations[$formationLibelle] = $formation;
                        }
                    }

                    $domaineLibelle = $line[$domaineColumn];
                    if ($domaineLibelle !== '') {
                        $domaine = $this->getDomaineService()->getDomaineByLibelle($domaineLibelle);
                        if ($domaine === null) {
                            $nouveauxDomaines[$domaineLibelle] = $domaineLibelle;
                        } else {
                            $domaines[$domaineLibelle] = $domaine;
                        }
                    }

                    if (!in_array($line[$modaliteColumn], ['Présentiel', 'Distanciel', 'Mixte'])) {
                        $error[] = "Modalité [".$line[$modaliteColumn]."] non reconnue !";
                    }
                }

                //Création & ajout
                if ($mode === 'import' && empty($error)) {
                    foreach ($array as $line) {

                        $axeLibelle = trim($line[$axeColumn]);
                        if (!isset($axes[$axeLibelle])) {
                            $axe = $this->getAxeService()->createAxe($axeLibelle);
                            $axes[$axeLibelle] = $axe;
                        } else {
                            $axe = $axes[$axeLibelle];
                        }

                        $themeLibelle = trim($line[$themeColumn]);
                        if (!isset($themes[$themeLibelle])) {
                            $theme = $this->getFormationGroupeService()->createFormationGroupe($themeLibelle, $axe);
                            $themes[$themeLibelle] = $theme;
                        } else {
                            $theme = $themes[$themeLibelle];
                        }

                        $formationLibelle = trim($line[$formationColumn]);
                        if (!isset($formations[$formationLibelle])) {
                            $formation = $this->getFormationService()->createFormation($formationLibelle, $theme);
                            $formations[$formationLibelle] = $formation;
                        } else {
                            $formation = $formations[$formationLibelle];
                        }

                        $domaineLibelle = trim($line[$domaineColumn]);
                        if ($domaineLibelle !== '') {
                            if (!isset($domaines[$domaineLibelle])) {
                                $domaine = $this->getDomaineService()->createDomaine($domaineLibelle);
                                $domaines[$domaineLibelle] = $domaine;
                            } else {
                                $domaine = $domaines[$domaineLibelle];
                            }
                        }

                        if ($domaine !== null && !$formation->hasDomaine($domaine)) {
                            $formation->addDomaine($domaine);
                            $this->getFormationService()->update($formation);
                        }

                        $description = $line[$descriptionColumn];
                        $objectif = $line[$descriptionColumn];
                        $programme = $line[$programmeColumn];
                        $modalite = $line[$modaliteColumn];

                        $formation->setDescription($description);
                        $formation->setObjectifs($objectif);
                        $formation->setProgramme($programme);
                        $formation->setType($modalite);
                        $this->getFormationService()->update($formation);


                        if (!$formation->hasPlanDeFormation($plan)) {
                            $formation->addPlanDeForamtion($plan);
                            $this->getFormationService()->update($formation);
                        }
                    }
                }

                if ($mode !== 'import') {
                    $title = "Importation d'un plan de formation (Prévisualisation)";
                }
                if ($mode === 'import') {
                    $title = "Importation de chaînes hiérarchiques (Importation)";
                }
                $warning = [];
                if (!empty($nouveauxAxes)) {
                    $warningAxe = "Nouveau·x axe·s : <ul>";
                    foreach ($nouveauxAxes as $nouveauAxe) $warningAxe .= "<li>" . $nouveauAxe . "</li>";
                    $warningAxe .= "</ul>";
                    $warning[] = $warningAxe;
                }

                if (!empty($nouveauxThemes)) {
                    $warningTheme = "Nouveau·x thème·s : <ul>";
                    foreach ($nouveauxThemes as $nouveauTheme) $warningTheme .= "<li>" . $nouveauTheme . "</li>";
                    $warningTheme .= "</ul>";
                    $warning[] = $warningTheme;
                }

                if (!empty($nouveauxFormations)) {
                    $warningFormation = "Nouvelle·s formation·s : <ul>";
                    foreach ($nouveauxFormations as $nouveauFormation) $warningFormation .= "<li>" . $nouveauFormation . "</li>";
                    $warningFormation .= "</ul>";
                    $warning[] = $warningFormation;
                }

                if (!empty($nouveauxDomaines)) {
                    $warningDomaine = "Nouveau·x domaine·s : <ul>";
                    foreach ($nouveauxDomaines as $nouveauDomaine) $warningDomaine .= "<li>" . $nouveauDomaine . "</li>";
                    $warningDomaine .= "</ul>";
                    $warning[] = $warningDomaine;
                }
            }

            return new ViewModel([
                'title' => $title,
                'fichier_path' => $fichier_path,
                'form' => $form,
                'mode' => $mode,
                'error' => $error,
                'warning' => $warning,
//                'agents' => $agents,
                'array' => $array,
            ]);
        }

        $vm = new ViewModel([
            'title' => "Importation d'un plan de formation",
            'form' => $form,
        ]);
        return $vm;
    }
}