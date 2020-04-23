<?php

namespace Application\Controller;

use Application\Entity\Db\ActiviteDescription;
use Application\Entity\Db\Application;
use Application\Entity\Db\Competence;
use Application\Entity\Db\Expertise;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use Application\Entity\Db\FicheposteApplicationRetiree;
use Application\Entity\Db\FicheposteCompetenceRetiree;
use Application\Entity\Db\FicheposteFormationRetiree;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\Formation;
use Application\Entity\Db\SpecificitePoste;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierFormAwareTrait;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierAgent\AssocierAgentFormAwareTrait;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\AssocierPoste\AssocierPosteFormAwareTrait;
use Application\Form\AssocierTitre\AssocierTitreFormAwareTrait;
use Application\Form\Expertise\ExpertiseFormAwareTrait;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Form\SpecificitePoste\SpecificitePosteFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesServiceAwareTrait;
use Application\Service\CompetencesRetirees\CompetencesRetireesServiceAwareTrait;
use Application\Service\Expertise\ExpertiseServiceAwareTrait;
use Application\Service\Export\FichePoste\FichePostePdfExporter;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\FormationsRetirees\FormationsRetireesServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FichePosteController extends AbstractActionController {
    /** Trait utilitaire */
    use DateTimeAwareTrait;

    /** Service **/
    use AgentServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ActiviteServiceAwareTrait;
    use ActivitesDescriptionsRetireesServiceAwareTrait;
    use ApplicationsRetireesServiceAwareTrait;
    use CompetencesRetireesServiceAwareTrait;
    use FormationsRetireesServiceAwareTrait;
    use ExpertiseServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;

    /** Form **/
    use AjouterFicheMetierFormAwareTrait;
    use AssocierAgentFormAwareTrait;
    use AssocierPosteFormAwareTrait;
    use AssocierTitreFormAwareTrait;
    use SpecificitePosteFormAwareTrait;
    use ExpertiseFormAwareTrait;

    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function indexAction()
    {
        $fiches             = $this->getFichePosteService()->getFichesPostes();

        $fichesCompletes = [];
        $fichesIncompletes = [];
        $ficheVides = [];
        foreach ($fiches as $fiche) {
            if ($fiche->isComplete()) $fichesCompletes[] = $fiche;
            else {
                if ($fiche->isVide()) $ficheVides[] = $fiche;
                else $fichesIncompletes[] = $fiche;
            }
        }


        return new ViewModel([
            'fiches' => $fiches,
            'fichesIncompletes' => $fichesIncompletes,
            'fichesVides' => $ficheVides,
            'fichesCompletes'       => $fichesCompletes,
        ]);
    }

    public function ajouterAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $fiche = new FichePoste();
        $fiche->setAgent($agent);
        $this->getFichePosteService()->create($fiche);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], [], true);
    }

    public function dupliquerAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $fiches = $this->getFichePosteService()->getFichesPostesByStructures($structures, true);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheId = $data['fiche'];
            $fiche = $this->getFichePosteService()->getFichePoste($ficheId);

            $nouvelleFiche = new FichePoste();
            $nouvelleFiche->setLibelle($fiche->getLibelle());
            $nouvelleFiche->setAgent($agent);

            //dupliquer specificite
            if ($fiche->getSpecificite()) {
                $specifite = $fiche->getSpecificite()->clone_it();
                $this->getSpecificitePosteService()->create($specifite);
                $nouvelleFiche->setSpecificite($specifite);
            }
            $nouvelleFiche = $this->getFichePosteService()->create($nouvelleFiche);

            //dupliquer fiche metier externe
            foreach ($fiche->getFichesMetiers() as $ficheMetierExterne) {
                $nouvelleFicheMetier = $ficheMetierExterne->clone_it();
                $nouvelleFicheMetier->setFichePoste($nouvelleFiche);
                $this->getFichePosteService()->createFicheTypeExterne($nouvelleFicheMetier);
            }

            /**  Commenter pour eviter perte de temps et clignotement de la modal */
            return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $nouvelleFiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
            //exit();
        }

        return new ViewModel([
            'title' => "Duplication d'une fiche de poste pour ".$agent->getDenomination()."",
            'structure' => $structure,
            'agent' => $agent,
            'fiches' => $fiches,
        ]);
    }


    public function afficherAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $titre = 'Fiche de poste <br/>';
        $titre .= '<strong>';
        if ($fiche->getFicheTypeExternePrincipale()) {
            $titre .= $fiche->getFicheTypeExternePrincipale()->getFicheType()->getMetier()->getLibelle();
        } else {
            $titre .= "<span class='icon attention' style='color:darkred;'></span> Aucun fiche principale";
        }
        if($fiche->getLibelle() !== null) {
            $titre .= "(" .$fiche->getLibelle(). ")";
        }
        $titre .= '</strong>';

        /** @var DateTime $date */
        $date = $this->getDateTime();
        $applications = $this->getFichePosteService()->getApplicationsDictionnaires($fiche, $date);
        $competences = $this->getFichePosteService()->getCompetencesDictionnaires($fiche, $date);
        $formations = $this->getFichePosteService()->getFormationsDictionnaires($fiche, $date);
        $activites = $this->getFichePosteService()->getActivitesDictionnaires($fiche, $date);

        return new ViewModel([
            'title' => $titre,
           'fiche' => $fiche,
            'applications' => $applications,
            'competences' => $competences,
            'formations' => $formations,
            'activites' => $activites,
        ]);
    }

    public function editerAction()
    {
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);


        /** @var DateTime $date */
        $date = $this->getDateTime();
        /** @var FichePoste $fiche */
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste', false);
        if ($fiche === null) $fiche = $this->getFichePosteService()->getLastFichePoste();

        $applications = $this->getFichePosteService()->getApplicationsDictionnaires($fiche, $date);
        $competences = $this->getFichePosteService()->getCompetencesDictionnaires($fiche, $date);
        $formations = $this->getFichePosteService()->getFormationsDictionnaires($fiche, $date);
        $activites = $this->getFichePosteService()->getActivitesDictionnaires($fiche, $date);

        return new ViewModel([
            'fiche' => $fiche,
            'structure' => $structure,
            'applications' => $applications,
            'competences' => $competences,
            'formations' => $formations,
            'activites' => $activites,
        ]);
    }

    public function historiserAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->historise($fiche);

        $retour  = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function restaurerAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->restore($fiche);

        $retour  = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function detruireAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        $structureId = $this->params()->fromQuery('structure');
        $params = [];
        if ($structureId !== null) $params["structure"] = $structureId;

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getFichePosteService()->delete($fiche);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fiche !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la fiche de poste  de ". (($fiche->getAgent())?$fiche->getAgent()->getDenomination():"[Aucun Agent]"),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-poste/detruire', ["fiche-poste" => $fiche->getId()], ["query" => $params], true),
            ]);
        }
        return $vm;
    }

    /** TITRE *********************************************************************************************************/

    public function associerTitreAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var AssocierAgentForm $form */
        $form = $this->getAssocierTitreForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/associer-titre', ['fiche-poste' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un titre',
            'form' => $form,
        ]);
        return $vm;
    }

    /** AGENT *********************************************************************************************************/

    public function associerAgentAction()
    {
        $structureId = $this->params()->fromQuery('structure');
        $sousstructure = ($this->params()->fromQuery('sous-structure') == '1');

        $structure = $this->getStructureService()->getStructure($structureId);

        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var AssocierAgentForm $form */
        $form = $this->getAssocierAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/associer-agent', ['fiche-poste' => $fiche->getId()], [], true));

        if ($structure !== null) {
            $form = $form->reinitWithStructure($structure, $sousstructure);
        }
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un agent',
            'form' => $form,
            'agents' => $this->getAgentService()->getAgents(),
        ]);
        return $vm;
    }

    /** POSTE *********************************************************************************************************/

    public function associerPosteAction()
    {
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);
        $sousstructure = ($this->params()->fromQuery('sous-structure') == '1');
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var AssocierPosteForm $form */
        $form = $this->getAssocierPosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/associer-poste', ['fiche-poste' => $fiche->getId()], [], true));

        if ($structure !== null) {
            $form = $form->reinitWithStructure($structure, $sousstructure);
        }
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un poste',
            'form' => $form,
        ]);
        return $vm;

    }

    /** FICHE METIER **************************************************************************************************/

    public function ajouterFicheMetierAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        $ficheTypeExterne = new FicheTypeExterne();
        $form = $this->getAjouterFicheTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/ajouter-fiche-metier', ['fiche-poste' => $fiche->getId()], [], true));
        $form->bind($ficheTypeExterne);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            $res = $this->checkValidite($fiche, $data);
            if ($res) return $res;

            if ($form->isValid()) {
                $ficheTypeExterne->setFichePoste($fiche);
                $this->getFichePosteService()->createFicheTypeExterne($ficheTypeExterne);

                if ($ficheTypeExterne->getPrincipale()) {
//                    var_dump('principale is 1');
                    foreach ($fiche->getFichesMetiers() as $ficheMetier) {
                        if ($ficheMetier !== $ficheTypeExterne && $ficheMetier->getPrincipale()) {
                            $ficheMetier->setPrincipale(false);
                            $this->getFichePosteService()->updateFicheTypeExterne($ficheMetier);
                        }
                    }
                }

                //comportement par defaut (ajout de toutes les activités)
                /** @var FicheMetier */
                $activites = $ficheTypeExterne->getFicheType()->getActivites();
                $tab = [];
                foreach ($activites as $activite) {
                    $tab[] = $activite->getActivite()->getId();
                }
                $text = implode(";",$tab);
                $ficheTypeExterne->setActivites($text);
                $this->getFichePosteService()->updateFicheTypeExterne($ficheTypeExterne);
            }
        }

        $vm = new ViewModel();
//        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'une fiche métier',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function modifierFicheMetierAction()
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFichePosteService()->getFicheTypeExterne($ficheTypeExterneId);
        $previous = $ficheTypeExterne->getQuotite();

        $form = $this->getAjouterFicheTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/modifier-fiche-metier', ['fiche-poste' => $fichePoste->getId(), 'fiche-type-externe' => $ficheTypeExterne->getId()], [], true));
        $form->bind($ficheTypeExterne);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            $data["old"] = $previous;
            $res = $this->checkValidite($fichePoste, $data);
            if ($res) return $res;

            if ($form->isValid()) {
                $ficheTypeExterne->setFichePoste($fichePoste);
                $this->getFichePosteService()->updateFicheTypeExterne($ficheTypeExterne);

                if ($ficheTypeExterne->getPrincipale()) {
                    foreach ($fichePoste->getFichesMetiers() as $ficheMetier) {
                        if ($ficheMetier !== $ficheTypeExterne && $ficheMetier->getPrincipale()) {
                            $ficheMetier->setPrincipale(false);
                            $this->getFichePosteService()->updateFicheTypeExterne($ficheMetier);
                        }
                    }
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'une fiche métier',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function retirerFicheMetierAction()
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFichePosteService()->getFicheTypeExterne($ficheTypeExterneId);

        if ($ficheTypeExterne && $fichePoste) $this->getFichePosteService()->deleteFicheTypeExterne($ficheTypeExterne);

        return $this->redirect()->toRoute('fiche-poste/editer',['fiche-poste' => $fichePoste->getId()], [], true);
    }

    public function selectionnerActiviteAction()
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFichePosteService()->getFicheTypeExterne($ficheTypeExterneId);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $result = [];
            foreach ($data as $key => $value) {
                if ($value === 'on') $result[] = $key;
            }
            $result = implode(";", $result);
            $ficheTypeExterne->setActivites($result);
            $this->getFichePosteService()->updateFicheTypeExterne($ficheTypeExterne);
        }

        return new ViewModel([
            'title' => 'Liste des activités de la fiche métier <br/> <strong>'. $ficheTypeExterne->getFicheType()->getMetier() .'</strong>',
            'fichePoste' => $fichePoste,
            'ficheTypeExterne' => $ficheTypeExterne,
        ]);
    }


    /**
     * @param FichePoste $fiche
     * @param array $data
     * @return ViewModel|void
     */
    private function checkValidite($fiche, $data)
    {
        $cut = false;
        if ($data['est_principale'] === "1"  && ((int) $data['quotite']) < 50) {
            $cut = true;
            $this->flashMessenger()->addErrorMessage("La fichie métier principale doit avoir une quotité d'au moins 50%.");
        }
        if ($data['est_principale'] === "0" && ((int) $data['quotite']) > 50) {
            $cut = true;
            $this->flashMessenger()->addErrorMessage("La fichie métier non principale doit avoir une quotité d'au plus 50%.");
        }
        if ($fiche->getQuotiteTravaillee() + ((int) $data['quotite']) - ((int) $data['old']) > 100) {
            $cut = true;
            $this->flashMessenger()->addErrorMessage("La somme des quotités travaillées ne peut dépasser 100%.");
        }
        if ($cut) {
            return (new ViewModel(['title' => 'Informations saisies incorrectes']))->setTemplate('layout/flashMessage');
        }
    }

    public function exportAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        $exporter = new FichePostePdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'fiche' => $fiche,
        ]);
        $exporter->export('export.pdf');
        exit;
    }

    /** ApplicationConserveesService **********************************************************************************/

    public function selectionnerApplicationsRetireesAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var array $applications*/
        $applications = $this->getFichePosteService()->getApplicationsDictionnaires($ficheposte, $this->getDateTime());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($applications as $item) {
                $application = $item['object'];
                $checked = (isset($data[$application->getId()]) AND $data[$application->getId()] === "on");

                if ($checked === false AND $item['conserve'] === true) {
                    $this->getApplicationsRetireesService()->add($ficheposte, $application);
                }
                if ($checked === true AND $item['conserve'] === false) {
                    $this->getApplicationsRetireesService()->remove($ficheposte, $application);
                }
            }
        }

        return new ViewModel([
            'title' => "Sélection des applications pour la fiche de poste",
            'ficheposte' => $ficheposte,
            'applications' => $applications,
        ]);
    }

    /** Compétences conservées ****************************************************************************************/

    public function selectionnerCompetencesRetireesAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var array $competences*/
        $competences = $this->getFichePosteService()->getCompetencesDictionnaires($ficheposte, $this->getDateTime());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($competences as $item) {
                $competence = $item['object'];
                $checked = (isset($data[$competence->getId()]) AND $data[$competence->getId()] === "on");

                if ($checked === false AND $item['conserve'] === true) {
                    $this->getCompetencesRetireesService()->add($ficheposte, $competence);
                }
                if ($checked === true AND $item['conserve'] === false) {
                    $this->getCompetencesRetireesService()->remove($ficheposte, $competence);
                }
            }
        }

        return new ViewModel([
            'title' => "Sélection des formations pour la fiche de poste",
            'ficheposte' => $ficheposte,
            'competences' => $competences,
        ]);
    }

    /** Formation conservées ****************************************************************************************/

    public function selectionnerFormationsRetireesAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var array $formations*/
        $formations = $this->getFichePosteService()->getFormationsDictionnaires($ficheposte, $this->getDateTime());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($formations as $item) {
                $formation = $item['object'];
                $checked = (isset($data[$formation->getId()]) AND $data[$formation->getId()] === "on");

                if ($checked === false AND $item['conserve'] === true) {
                    $this->getFormationsRetireesService()->add($ficheposte, $formation);
                }
                if ($checked === true AND $item['conserve'] === false) {
                    $this->getFormationsRetireesService()->remove($ficheposte, $formation);
                }
            }
        }

        return new ViewModel([
            'title' => "Sélection des formations pour la fiche de poste",
            'ficheposte' => $ficheposte,
            'formations' => $formations,
        ]);
    }

    /** Descriprition conservées **************************************************************************************/

    public function selectionnerDescriptionsRetireesAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        /**
         * @var ActiviteDescription[] $descriptions
         * @var FicheposteActiviteDescriptionRetiree[] $retirees
         */
        $descriptions = $activite->getDescriptions();
        $retirees = $this->getActivitesDescriptionsRetireesService()->getActivitesDescriptionsRetirees($ficheposte, $fichemetier, $activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($descriptions as $description) {
                $found = null;
                foreach ($retirees as $retiree) {
                    if ($retiree->getHistoDestruction() === null AND $retiree->getDescription() === $description) {
                        $found = $retiree;
                    }
                }
                $checked = (isset($data[$description->getId()]) AND $data[$description->getId()] === "on");

                if ($found !== null AND $checked) $this->getActivitesDescriptionsRetireesService()->delete($found);
                if ($found === null AND !$checked) {
                    $item = new FicheposteActiviteDescriptionRetiree();
                    $item->setFichePoste($ficheposte);
                    $item->setFicheMetier($fichemetier);
                    $item->setActivite($activite);
                    $item->setDescription($description);
                    $this->getActivitesDescriptionsRetireesService()->create($item);
                }
            }
//            return $this->redirect()->toRoute('fiche-poste/selectionner-applications-conservees', ['fiche-poste' => $ficheposte->getId(), 'fiche-metier' => $fichemetier->getId()], [], true);
        }

        return new ViewModel([
            'title' => "Sélection de sous-activité pour l'activité [" .$activite->getLibelle() ."]",
            'ficheposte' => $ficheposte,
            'fichemetier' => $fichemetier,
            'activite' => $activite,
            'descriptions' => $descriptions,
            'retirees' => $retirees,
        ]);
    }

    /** EXPERTISE *****************************************************************************************************/

    public function ajouterExpertiseAction()
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $expertise = new Expertise();
        $expertise->setFicheposte($ficheposte);

        $form = $this->getExpertiseForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/ajouter-expertise', ['fiche-poste' => $ficheposte->getId()], [], true));
        $form->bind($expertise);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getExpertiseService()->create($expertise);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une expertise",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierExpertiseAction()
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);

        $form = $this->getExpertiseForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/modifier-expertise', ['expertise' => $expertise->getId()], [], true));
        $form->bind($expertise);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getExpertiseService()->update($expertise);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une expertise",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserExpertiseAction()
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);
        $this->getExpertiseService()->historise($expertise);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $expertise->getFicheposte()->getId()], [], true);
    }

    public function restaurerExpertiseAction()
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);
        $this->getExpertiseService()->restore($expertise);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $expertise->getFicheposte()->getId()], [], true);
    }

    public function supprimerExpertiseAction()
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);
        $this->getExpertiseService()->delete($expertise);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $expertise->getFicheposte()->getId()], [], true);
    }

    /** SPECIFICITE ***************************************************************************************************/

    public function editerSpecificiteAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        $specificite = $fiche->getSpecificite();
        if ($specificite === null) {
            $specificite = new SpecificitePoste();
            $fiche->setSpecificite($specificite);
            $this->getSpecificitePosteService()->create($specificite);
        }

        /** @var SpecificitePosteForm $form */
        $form = $form = $this->getSpecificitePosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/editer-specificite', ['fiche' => $fiche->getId()], [], true));
        $form->bind($specificite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $specificite->setFiche($fiche);
                $this->getSpecificitePosteService()->update($specificite);
                $this->getFichePosteService()->update($fiche);
            }
        }

        return new ViewModel([
            'title' => 'Modifier spécificité du poste',
            'form' => $form,
        ]);

    }

}