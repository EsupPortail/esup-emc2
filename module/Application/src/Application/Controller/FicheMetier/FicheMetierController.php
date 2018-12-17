<?php

namespace Application\Controller\FicheMetier;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\MissionComplementaire;
use Application\Entity\Db\SpecificitePoste;
use Application\Form\Agent\AgentForm;
use Application\Form\Agent\MissionComplementaireForm;
use Application\Form\FicheMetier\AssocierAgentForm;
use Application\Form\FicheMetier\AssocierMetierTypeForm;
use Application\Form\FicheMetier\AssocierPosteForm;
use Application\Form\FicheMetier\FicheMetierCreationForm;
use Application\Form\FicheMetier\SpecificitePosteForm;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends AbstractActionController
{
    use FicheMetierServiceAwareTrait;
    use AgentServiceAwareTrait;
    use ActiviteServiceAwareTrait;

    public function indexAction() {

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiers();

        return new ViewModel([
            'fichesMetiers' => $fichesMetiers,
        ]);
    }

    public function afficherAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $activites = [];
        if ($fiche->getMetierType()) {
            $activites = $this->getActiviteService()->getActivitesByFicheMetierType($fiche->getMetierType());
        }

        return new ViewModel([
            'fiche' => $fiche,
            'activites' => $activites,
        ]);
    }

    public function historiserAction()
    {
        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $this->getFicheMetierService()->historiser($fiche);
        $this->redirect()->toRoute('fiche-metier',[], [], true);
    }

    public function restaurerAction()
    {
        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $this->getFicheMetierService()->restaurer($fiche);
        $this->redirect()->toRoute('fiche-metier',[], [], true);
    }

    public function editerAction()
    {
        $libelle = 'Environnement du poste de travail dans l\'organisation';
        return new ViewModel([
            'title' => "Édition de <em>".$libelle."</em>",
        ]);
    }

    public function creerAction()
    {
        /** @var FicheMetierCreationForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(FicheMetierCreationForm::class);
        $fiche = new FicheMetier();
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {

                $fiche = $this->getFicheMetierService()->creer($fiche);
                $this->redirect()->toRoute('fiche-metier/afficher', ['id' => $fiche->getId()], [], true);
            }
        }

        return new ViewModel([
           'form' => $form,
        ]);
    }

    /** ACTION ASSOCIÉES À L'AGENT ************************************************************************************/

    public function afficherAgentAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);
        $agent = $fiche->getAgent();

        return new ViewModel([
           'agent' => $agent,
        ]);
    }

    public function saisieManuelleAgentAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        $agent = $fiche->getAgent();
        /** @var AgentForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentForm::class);
        $form->bind($agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->create($agent);
                $this->getFicheMetierService()->update($fiche);
                $this->redirect()->toRoute('fiche-metier/afficher-agent', ['id' => $ficheId], [], true);
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function listerAgentsAction()
    {
        $agents = $this->getAgentService()->getAgents();

        return new ViewModel([
            'agents' => $agents,
        ]);
    }

    public function ajouterMissionComplementaireAction()
    {
        $agentId = $this->params()->fromRoute('agent');
        $agent = $this->getAgentService()->getAgent($agentId);

        $mission = new MissionComplementaire();

        /** @var MissionComplementaireForm $form */
        $form = $form = $this->getServiceLocator()->get('FormElementManager')->get(MissionComplementaireForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/ajouter-mission-complementaire', [], [], true));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $mission->setAgent($agent);
                $this->getAgentService()->createMissionComplementaire($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une mission complementaire',
            'form' => $form,
        ]);
        return $vm;


    }

    public function editerMissionComplementaireAction()
    {
        $missionId = $this->params()->fromRoute('id');
        $mission = $this->getAgentService()->getMissionComplementaire($missionId);
        $agent = $mission->getAgent();

        /** @var MissionComplementaireForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MissionComplementaireForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/editer-mission-complementaire', ['id' => $mission->getId()], [], true));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $mission->setAgent($agent);
                $this->getAgentService()->updateMissionComplementaire($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer une mission complementaire',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerMissionComplementaireAction()
    {
        $missionId = $this->params()->fromRoute('id');
        $mission = $this->getAgentService()->getMissionComplementaire($missionId);

        $this->getAgentService()->deleteMissionComplementaire($mission);
        $this->redirect()->toRoute('fiche-metier/afficher-agent', ['id' => $mission->getAgent()->getId()], [], true);
    }

    /** FICHE METIER TYPE *********************************************************************************************/

    public function associerMetierTypeAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        /** @var AssocierMetierTypeForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AssocierMetierTypeForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/associer-metier-type', ['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }


        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un metier type',
            'form' => $form,
        ]);
        return $vm;

    }

    /** AGENT *********************************************************************************************************/

    public function associerAgentAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        /** @var AssocierAgentForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AssocierAgentForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/associer-agent', ['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }


        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un agent',
            'form' => $form,
        ]);
        return $vm;

    }

    /** POSTE *********************************************************************************************************/

    public function associerPosteAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        /** @var AssocierAgentForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AssocierPosteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/associer-poste', ['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
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
    /** SPECIFICITE POSTE *********************************************************************************************/

    public function editerSpecificitePosteAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        $specificite = null;
        if ($fiche->getSpecificite()) {
            $specificite = $fiche->getSpecificite();
        } else {
            $specificite = new SpecificitePoste();
            $fiche->setSpecificite($specificite);
            $this->getFicheMetierService()->createSpecificitePoste($specificite);
        }

        /** @var SpecificitePosteForm $form */
        $form = $form = $this->getServiceLocator()->get('FormElementManager')->get(SpecificitePosteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/specificite', ['fiche' => $fiche->getId()], [], true));
        $form->bind($specificite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $specificite->setFiche($fiche);
                $this->getFicheMetierService()->updateSpecificitePoste($specificite);
                $this->getFicheMetierService()->update($fiche);
            }
        }

        return new ViewModel([
            'title' => 'Éditer spécificité du poste',
            'form' => $form,
        ]);

    }
}