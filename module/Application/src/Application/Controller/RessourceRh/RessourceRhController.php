<?php

namespace Application\Controller\RessourceRh;

use Application\Entity\Db\AgentStatus;
use Application\Form\RessourceRh\AgentStatusForm;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    use RessourceRhServiceAwareTrait;

    public function indexAction()
    {
        $status = $this->getRessourceRhService()->getAgentStatusListe('libelle');
        $BAPs = null;
        $REFERENSs = null;
        $REMEs = null;
        $bibliotheques = null;

        return new ViewModel([
            'status' => $status,
//            'BAPs' => $BAPs,
//            'REFERENSs' => $REFERENSs,
//            'REMEs' => $REMEs,
//            'bibliotheques' => $bibliotheques,
        ]);
    }

    public function creerAgentStatusAction()
    {
        $status = new AgentStatus();

        /** @var AgentStatusForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentStatusForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/creer-agent-status', [], [], true));
        $form->bind($status);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createAgentStatus($status);
            }
        }

        return new ViewModel([
            'title' => 'Ajouter un nouveau status',
            'form' => $form,
        ]);
    }

    public function modifierAgentStatusAction()
    {
        $statusId = $this->params()->fromRoute('id');
        $status = $this->getRessourceRhService()->getAgentStatus($statusId);

        /** @var AgentStatusForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentStatusForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/modifier-agent-status', [], [], true));
        $form->bind($status);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateAgentStatus($status);
            }
        }

        return new ViewModel([
            'title' => 'Modifier un nouveau status',
            'form' => $form,
        ]);
    }

    public function effacerAgentStatusAction()
    {
        $statusId = $this->params()->fromRoute('id');
        $status = $this->getRessourceRhService()->getAgentStatus($statusId);

        if ($status !== null) {
            $this->getRessourceRhService()->deleteAgentStatus($status);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }
}