<?php

namespace Application\Controller\FichePoste;

use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierAgent\AssocierAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FichePosteController extends AbstractActionController {
    /** Service **/
    use AgentServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    /** Form **/
    use AssocierAgentFormAwareTrait;

    public function indexAction()
    {
        $fiches = $this->getFichePosteService()->getFichesPostes();

        return new ViewModel([
            'fiches' => $fiches,
        ]);
    }

    public function ajouterAction()
    {

    }

    public function afficherAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        return new ViewModel([
            'title' => 'Fiche de poste <br/> <em>'.$fiche->getLibelle().'</em>',
           'fiche' => $fiche,
        ]);
    }

    public function editerAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        return new ViewModel([
            'fiche' => $fiche,
        ]);
    }

    public function historiserAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->historise($fiche);
        $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function restaurerAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->restore($fiche);
        $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function detruireAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');
        $this->getFichePosteService()->delete($fiche);
        $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function associerAgentAction()
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this, 'fiche-poste');

        /** @var AssocierAgentForm $form */
        $form = $this->getAssocierAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/associer-agent', ['fiche-poste' => $fiche->getId()], [], true));
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
}