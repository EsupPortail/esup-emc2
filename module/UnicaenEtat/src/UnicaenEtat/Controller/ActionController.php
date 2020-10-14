<?php

namespace UnicaenEtat\Controller;

use UnicaenEtat\Entity\Db\Action;
use UnicaenEtat\Form\Action\ActionFormAwareTrait;
use UnicaenEtat\Service\Action\ActionServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ActionController extends AbstractActionController
{
    use ActionServiceAwareTrait;
    use ActionFormAwareTrait;

    public function indexAction()
    {
        $actions = $this->getActionService()->getActions();
        return new ViewModel([
            'actions' => $actions,
        ]);
    }

    public function ajouterAction()
    {
        $action = new Action();

        $form = $this->getActionForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/action/ajouter', [], [], true));
        $form->bind($action);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActionService()->create($action);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-etat/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type d'action",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $action = $this->getActionService()->getRequestedAction($this);

        $form = $this->getActionForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/action/modifier', ['action' => $action->getId()], [], true));
        $form->bind($action);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActionService()->update($action);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-etat/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type d'action",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $action = $this->getActionService()->getRequestedAction($this);
        $this->getActionService()->historise($action);
        return $this->redirect()->toRoute('unicaen-etat/etat-type');
    }

    public function restaurerAction()
    {
        $action = $this->getActionService()->getRequestedAction($this);
        $this->getActionService()->restore($action);
        return $this->redirect()->toRoute('unicaen-etat/etat-type');
    }

    public function supprimerAction()
    {
        $action = $this->getActionService()->getRequestedAction($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getActionService()->delete($action);
            exit();
        }

        $vm = new ViewModel();
        if ($action !== null) {
            $vm->setTemplate('unicaen-etat/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type d'action " . $action->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-etat/action/supprimer', ["action" => $action->getId()], [], true),
            ]);
        }
        return $vm;
    }

}