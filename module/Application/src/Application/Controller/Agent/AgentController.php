<?php

namespace Application\Controller\Agent;

use Application\Entity\Db\Agent;
use Application\Form\Agent\AgentForm;
use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    use AgentServiceAwareTrait;

    public function indexAction()
    {
        $agents = $this->getAgentService()->getAgents();
        return  new ViewModel([
            'agents' => $agents,
        ]);
    }

    public function afficherAction() {

        $agentId = $this->params()->fromRoute('id');
        $agent = $this->getAgentService()->getAgent($agentId);

        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
        ]);
    }

    public function ajouterAction() {

        /** @var Agent $agent */
        $agent = new Agent();

        /** @var AgentForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter', [], [], true));
        $form->bind($agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->create($agent);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/agent/modifier');
        $vm->setVariables([
            'title' => 'Ajouter un agent',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() {

        /** @var Agent $agent */
        $agentId = $this->params()->fromRoute('id');
        $agent = $this->getAgentService()->getAgent($agentId);

        /** @var AgentForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier', ['id' => $agent->getId()], [], true));
        $form->bind($agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->update($agent);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/agent/modifier');
        $vm->setVariables([
            'title' => 'Modifier un agent',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerAction() {

        /** @var Agent $agent */
        $agentId = $this->params()->fromRoute('id');
        $agent = $this->getAgentService()->getAgent($agentId);

        $this->getAgentService()->delete($agent);

        $this->redirect()->toRoute('agent', [], [], true);
    }
}