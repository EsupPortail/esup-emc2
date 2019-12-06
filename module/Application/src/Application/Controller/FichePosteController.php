<?php

namespace Application\Controller;

use Application\Entity\Db\Application;
use Application\Entity\Db\Competence;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteApplicationConservee;
use Application\Entity\Db\FicheposteCompetenceConservee;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierFormAwareTrait;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierAgent\AssocierAgentFormAwareTrait;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\AssocierPoste\AssocierPosteFormAwareTrait;
use Application\Form\AssocierTitre\AssocierTitreFormAwareTrait;
use Application\Form\FichePosteCreation\FichePosteCreationFormAwareTrait;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Form\SpecificitePoste\SpecificitePosteFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\ApplicationsConservees\ApplicationsConserveesServiceAwareTrait;
use Application\Service\CompetencesConservees\CompetencesConserveesServiceAwareTrait;
use Application\Service\Export\FichePoste\FichePostePdfExporter;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FichePosteController extends AbstractActionController {
    /** Service **/
    use AgentServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ApplicationsConserveesServiceAwareTrait;
    use CompetencesConserveesServiceAwareTrait;

    /** Form **/
    use AjouterFicheMetierFormAwareTrait;
    use AssocierAgentFormAwareTrait;
    use AssocierPosteFormAwareTrait;
    use AssocierTitreFormAwareTrait;
    use FichePosteCreationFormAwareTrait;
    use SpecificitePosteFormAwareTrait;

    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function indexAction()
    {
        $fiches = $this->getFichePosteService()->getFichesPostes();

        return new ViewModel([
            'fiches' => $fiches,
        ]);
    }


    public function ajouterAction()
    {
        $fiche = new FichePoste();
        $form = $this->getFichePosteCreationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/ajouter', ['fiche-poste' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->create($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Libelle de la fiche de poste',
            'form' => $form,
        ]);
        return $vm;
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

        return new ViewModel([
            'title' => $titre,
           'fiche' => $fiche,
        ]);
    }

    public function editerAction()
    {
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);


        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste', false);
        if ($fiche === null) $fiche = $this->getFichePosteService()->getLastFichePoste();

        return new ViewModel([
            'fiche' => $fiche,
            'structure' => $structure,
        ]);
    }

    public function historiserAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->historise($fiche);
        return $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function restaurerAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->restore($fiche);
        return $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function detruireAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);
        $params = [];
        if ($structure !== null) $params["structure"] = $structure->getId();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFichePosteService()->delete($fiche);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($fiche !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la fiche de poste  de " . $fiche->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-poste/detruire', ["affectation" => $fiche->getId()], ["query" => $params], true),
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

    /** SPECIFICITE ***************************************************************************************************/

    public function editerSpecificiteAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        $specificite = $fiche->getSpecificite();
        if ($specificite === null) {
            $specificite = new SpecificitePoste();
            $fiche->setSpecificite($specificite);
            $this->getFichePosteService()->createSpecificitePoste($specificite);
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
                $this->getFichePosteService()->updateSpecificitePoste($specificite);
                $this->getFichePosteService()->update($fiche);
            }
        }

        return new ViewModel([
            'title' => 'Modifier spécificité du poste',
            'form' => $form,
        ]);

    }

    /**
     * @param FichePoste $fiche
     * @param array $data
     * @return ViewModel
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

    /** Document pour la signature en présidence */
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

    public function selectionnerApplicationsConserveesAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        /**
         * @var Application[] $applications
         * @var FicheposteApplicationConservee[] $conservees
         */
        $applications = $fichemetier->getApplications()->toArray();
        $conservees = $ficheposte->getApplicationsConservees()->toArray();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($applications as $application) {
                $found = null;
                foreach ($conservees as $conservee) {
                    if ($conservee->getHistoDestruction() === null AND $conservee->getApplication() === $application) {
                        $found = $conservee;
                    }
                }
                $checked = (isset($data[$application->getId()]) AND $data[$application->getId()] === "on");

                if ($found !== null AND !$checked) $this->getApplicationsConserveesService()->delete($found);
                if ($found === null AND $checked) {
                    $item = new FicheposteApplicationConservee();
                    $item->setFichePoste($ficheposte);
                    $item->setFicheMetier($fichemetier);
                    $item->setApplication($application);
                    $this->getApplicationsConserveesService()->create($item);
                }
            }
//            return $this->redirect()->toRoute('fiche-poste/selectionner-applications-conservees', ['fiche-poste' => $ficheposte->getId(), 'fiche-metier' => $fichemetier->getId()], [], true);
        }

        return new ViewModel([
            'title' => "Sélection d'applicaiton pour la fiche méfier [" .$fichemetier->getMetier()->getLibelle() ."]",
            'ficheposte' => $ficheposte,
            'fichemetier' => $fichemetier,
            'applications' => $applications,
            'conservees' => $conservees,
        ]);
    }

    public function testAffichageApplicationBlocAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $applications = $this->getFichePosteService()->getApplicationsAssocieesFicheMetier($ficheposte, $fichemetier);
        return new ViewModel([
            'ficheposte' => $ficheposte,
            'fichemetier' => $fichemetier,
            'applications' => $applications,
        ]);
    }

    /** Compétences conservées ****************************************************************************************/

    public function selectionnerCompetencesConserveesAction() {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        /**
         * @var Competence[] $competences
         * @var FicheposteCompetenceConservee[] $conservees
         */
        $competences = $fichemetier->getCompetences()->toArray();
        $conservees = $ficheposte->getCompetencesConservees()->toArray();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($competences as $competence) {
                $found = null;
                foreach ($conservees as $conservee) {
                    if ($conservee->getHistoDestruction() === null AND $conservee->getCompetence() === $competence) {
                        $found = $conservee;
                    }
                }
                $checked = (isset($data[$competence->getId()]) AND $data[$competence->getId()] === "on");

                if ($found !== null AND !$checked) $this->getCompetencesConserveesService()->delete($found);
                if ($found === null AND $checked) {
                    $item = new FicheposteCompetenceConservee();
                    $item->setFichePoste($ficheposte);
                    $item->setFicheMetier($fichemetier);
                    $item->setCompetence($competence);
                    $this->getCompetencesConserveesService()->create($item);
                }
            }
//            return $this->redirect()->toRoute('fiche-poste/selectionner-applications-conservees', ['fiche-poste' => $ficheposte->getId(), 'fiche-metier' => $fichemetier->getId()], [], true);
        }

        return new ViewModel([
            'title' => "Sélection d'applicaiton pour la fiche méfier [" .$fichemetier->getMetier()->getLibelle() ."]",
            'ficheposte' => $ficheposte,
            'fichemetier' => $fichemetier,
            'competences' => $competences,
            'conservees' => $conservees,
        ]);
    }
}