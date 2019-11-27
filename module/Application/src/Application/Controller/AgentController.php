<?php

namespace Application\Controller;

use Application\Entity\Db\AgentCompetence;
use Application\Form\Agent\AgentFormAwareTrait;
use Application\Form\AgentCompetence\AgentCompetenceFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    /** Trait utilisés pour les services */
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    /** Trait de formulaire */
    use AgentFormAwareTrait;
    use AgentCompetenceFormAwareTrait;

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
        return $this->redirect()->toRoute('index-personnel', [], [], true);
    }

    public function restaurerAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $this->getAgentService()->restoreAgentCompetence($competence);
        return $this->redirect()->toRoute('index-personnel', [], [], true);
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
                'action' => $this->url()->fromRoute('agent/detruire-agent-competence', ["competence" => $competence->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /**
     * @return JsonModel
     */
    public function rechercherIndividuAction() {
        if (($term = $this->params()->fromQuery('term'))) {
            $individus = $this->getIndividuService()->getIndividusByTerm($term);
            $result = [];
            /** @var Individu[] $individus */
            foreach ($individus as $individu) {
                $result[] = array(
                    'id'    => $individu->getCIndividuChaine(),
                    'label' => $individu->getPrenom()." ".(($individu->getNomUsage())?$individu->getNomUsage():$individu->getNomFamille()),
                    'extra' => $individu->getCSource()->__toString(),
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