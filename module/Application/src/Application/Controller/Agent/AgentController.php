<?php

namespace Application\Controller\Agent;

use Application\Entity\Db\Agent;
use Application\Form\Agent\AgentForm;
use Application\Form\Agent\AgentFormAwareTrait;
use Application\Form\Agent\AgentImportFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Octopus\Entity\Db\Individu;
use Octopus\Entity\Db\IndividuAffectationType;
use Octopus\Service\Individu\IndividuServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    /** Trait utilisés pour les services */
    use AgentServiceAwareTrait;
    use IndividuServiceAwareTrait;
    /** Trait utilisés pour les formulaires */
    use AgentFormAwareTrait;
    use AgentImportFormAwareTrait;

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
        $form = $this->getAgentForm();
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
        $form = $this->getAgentForm();
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

    public function importerAction()
    {
        $form = $this->getAgentImportForm();
        $form->setAttribute('method','post');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            var_dump($data);
            $individu = $this->getIndividuService()->getIndividu($data['agent']['id']);

            var_dump($individu->getNomUsage());
            var_dump($individu->getPrenom());
            foreach ($individu->getAffectations() as $affectation) {
                if ($affectation->getType()->getId() < 5) {
                    var_dump($affectation->getStructure()->getLibelleLong());
                    var_dump($affectation->getDateDebut()->format('d/m/Y'));
                    if ($affectation->getDateFin()) var_dump($affectation->getDateFin()->format('d/m/Y'));
                }
            }
            //$this->redirect()->toRoute('agent/importer');
        }

        return new ViewModel([
            'form' => $form,
        ]);
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
                    'extra' => ($individu->getCSource())->__toString(),
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