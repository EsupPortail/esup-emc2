<?php

namespace Application\Controller\Agent;

use Application\Form\Agent\AssocierMissionSpecifiqueFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Octopus\Entity\Db\Individu;
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
    use RessourceRhServiceAwareTrait;
    /** Trait utilisés pour les formulaires */
    use AssocierMissionSpecifiqueFormAwareTrait;

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

    public function associerMissionSpecifiqueAction()
    {
        $agent   = $this->getAgentService()->getRequestedAgent($this, 'agent');
        $form = $this->getAssocierMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/associer-mission-specifique', ['agent' => $agent->getId()], [], true));
        $form->bind($agent);

        /** @var  Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data['mission'] !== "") {
                $mission = $this->getRessourceRhService()->getMissionSpecifique($data['mission']);
                if ($mission) {
                    $agent->addMission($mission);
                    $this->getAgentService()->update($agent);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer une mission spécifique',
            'form' => $form,
        ]);
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