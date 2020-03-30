<?php

namespace Application\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentApplication;
use Application\Entity\Db\AgentCompetence;
use Application\Entity\Db\AgentFormation;
use Application\Entity\Db\Application;
use Application\Form\Agent\AgentFormAwareTrait;
use Application\Form\AgentApplication\AgentApplicationForm;
use Application\Form\AgentApplication\AgentApplicationFormAwareTrait;
use Application\Form\AgentCompetence\AgentCompetenceFormAwareTrait;
use Application\Form\AgentFormation\AgentFormationFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use RessourceRhServiceAwareTrait;

    use AgentFormAwareTrait;
    use AgentApplicationFormAwareTrait;
    use AgentCompetenceFormAwareTrait;
    use AgentFormationFormAwareTrait;
    use SelectionApplicationFormAwareTrait;

    public function indexAction() {
        $agents = $this->getAgentService()->getAgents();
        return  new ViewModel([
            'agents' => $agents,
        ]);
    }

    public function afficherAction() {

        $agent = $this->getAgentService()->getRequestedAgent($this, 'id');

        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
        ]);
    }

    public function modifierAction()
    {
        $agent   = $this->getAgentService()->getRequestedAgent($this, 'agent');
        $form = $this->getAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier', ['agent' => $agent->getId()], [], true));
        $form->bind($agent);

        /** @var  Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                    $this->getAgentService()->update($agent);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier l\'agent',
            'form' => $form,
        ]);
        return $vm;
    }

    /** Gestion des applications***************************************************************************************/

    public function ajouterAgentApplicationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $agentApplication = new AgentApplication();

        /** @var AgentApplicationForm $form */
        $form = $this->getAgentApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-application', [ 'agent' => $agent->getId() ], [], true));
        $form->bind($agentApplication);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $agentApplication->setAgent($agent);
                $this->getAgentService()->createAgentApplication($agentApplication);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une application maîtrisée par l'agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);
        return new ViewModel([
            'title' => "Affichage d'une application maîtrisée par un agent",
            'applicationAgent' => $applicationAgent,
        ]);
    }

    public function modifierAgentApplicationAction()
    {
        $agenApplication = $this->getAgentService()->getRequestedAgenApplication($this);
        $form = $this->getAgentApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-application', ['agent-application' => $agenApplication->getId()]));
        $form->bind($agenApplication);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->updateAgentApplication($agenApplication);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une application maîtrisée par un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);
        $this->getAgentService()->historiserAgentApplication($applicationAgent);
        return $this->redirect()->toRoute('agent/afficher', ['id' => $applicationAgent->getAgent()->getId()], [], true);
    }

    public function restaurerAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);
        $this->getAgentService()->restoreAgentApplication($applicationAgent);
        return $this->redirect()->toRoute('agent/afficher', ['id' => $applicationAgent->getAgent()->getId()], [], true);
    }

    public function detruireAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentApplication($applicationAgent);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($applicationAgent !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application  de " . $applicationAgent->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-application', ["agent-application" => $applicationAgent->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des compétences ***************************************************************************************/

    public function ajouterAgentCompetenceAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $competence = new AgentCompetence();
        $competence->setAgent($agent);
        $form = $this->getAgentCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-competence', ['agent' => $agent->getId()]));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->createAgentCompetence($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
           'title' => "Ajout d'une compétence associée à un agent",
           'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        return new ViewModel([
            'title' => "Affichage d'une compétence",
            'competence' => $competence,
        ]);
    }

    public function modifierAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $form = $this->getAgentCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-competence', ['agent-competence' => $competence->getId()]));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->updateAgentCompetence($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une compétence associée à un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $this->getAgentService()->historiserAgentCompetence($competence);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $competence->getAgent()->getId()], [], true);
    }

    public function restaurerAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $this->getAgentService()->restoreAgentCompetence($competence);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $competence->getAgent()->getId()], [], true);
    }

    public function detruireAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentCompetence($competence);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($competence !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la compétence  de " . $competence->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-competence', ["agent-competence" => $competence->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des formations ****************************************************************************************/

    public function ajouterAgentFormationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $agentFormation = new AgentFormation();
        $agentFormation->setAgent($agent);
        $form = $this->getAgentFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-formation', ['agent' => $agent->getId()]));
        $form->bind($agentFormation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->createAgentFormation($agentFormation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une formation associée à un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        return new ViewModel([
            'title' => "Affichage d'une formation",
            'competence' => $agentFormation,
        ]);
    }

    public function modifierAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        $form = $this->getAgentFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-formation', ['agent-formation' => $agentFormation->getId()]));
        $form->bind($agentFormation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->updateAgentFormation($agentFormation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une formation associée à un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        $this->getAgentService()->historiserAgentFormation($agentFormation);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentFormation->getAgent()->getId()], [], true);
    }

    public function restaurerAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        $this->getAgentService()->restoreAgentFormation($agentFormation);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentFormation->getAgent()->getId()], [], true);
    }

    public function detruireAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentFormation($agentFormation);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($agentFormation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la formation  de " . $agentFormation->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-formation', ["agent-formation" => $agentFormation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Recherche d'agent  ********************************************************************************************/

    public function rechercherAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term);
            $result = [];
            /** @var Agent[] $agents */
            foreach ($agents as $agent) {
                $result[] = array(
                    'id'    => $agent->getId(),
                    'label' => $agent->getDenomination(),
                    'extra' => "<span class='badge' style='background-color: slategray;'>".$agent->getSourceName()."</span>",
                );
            }
            usort($result, function($a, $b) {
                return strcmp($a['label'], $b['label']);
            });

            return new JsonModel($result);
        }
        exit;
    }


}