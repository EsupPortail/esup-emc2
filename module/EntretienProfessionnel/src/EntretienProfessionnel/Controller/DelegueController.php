<?php

namespace EntretienProfessionnel\Controller;

use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\Delegue;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Delegue\DelegueServiceAwareTrait;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DelegueController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use DelegueServiceAwareTrait;
    use StructureServiceAwareTrait;
    use SelectionAgentFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        $delegue = new Delegue();
        $delegue->setStructure($structure);
        $delegue->setCampagne($campagne);

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/ajouter-delegue', ['structure' => $structure->getId(), 'campagne' => $campagne->getId()], [], true));
        $form->bind($delegue);
        /** @see AgentController::rechercherAction() */
        $form->setUrlAgent($this->url()->fromRoute('agent/rechercher', [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $agent = null;
            if (isset($data['agent']) AND isset($data['agent']['id'])) {
                $agent = $this->getAgentService()->getAgent($data['agent']['id']);
            }
            if ($agent !== null) {
                $delegue->setAgent($agent);
                $this->getDelegueService()->create($delegue);
            }
        }

        $vm =  new ViewModel([
            'title' => "Ajout d'un&middot;e délégué&middot;e pour la campagne",
            'form' => $form,
        ]);
        $vm->setTemplate('entretien-professionnel/default/default-form');
        return $vm;

    }

    public function retirerAction() : Response
    {
        $delegue = $this->getDelegueService()->getRequestedDelegue($this);
        $this->getDelegueService()->delete($delegue);

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $delegue->getStructure()->getId()], ['fragment' => 'campagne_' . $delegue->getCampagne()->getId()], true);
    }
}