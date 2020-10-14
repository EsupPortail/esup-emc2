<?php

namespace UnicaenEtat\Controller;

use UnicaenEtat\Entity\Db\ActionType;
use UnicaenEtat\Form\ActionType\ActionTypeFormAwareTrait;
use UnicaenEtat\Service\ActionType\ActionTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ActionTypeController extends AbstractActionController
{
    use ActionTypeServiceAwareTrait;
    use ActionTypeFormAwareTrait;

    public function indexAction()
    {
        $actionTypes = $this->getActionTypeService()->getActionTypes();
        return new ViewModel([
            'actionTypes' => $actionTypes,
        ]);
    }

    public function ajouterAction()
    {
        $actionType = new ActionType();

        $form = $this->getActionTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/action-type/ajouter', [], [], true));
        $form->bind($actionType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActionTypeService()->create($actionType);
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
        $actionType = $this->getActionTypeService()->getRequestedActionType($this);

        $form = $this->getActionTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/action-type/modifier', ['action-type' => $actionType->getId()], [], true));
        $form->bind($actionType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActionTypeService()->update($actionType);
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
        $actionType = $this->getActionTypeService()->getRequestedActionType($this);
        $this->getActionTypeService()->historise($actionType);
        return $this->redirect()->toRoute('unicaen-etat/etat-type');
    }

    public function restaurerAction()
    {
        $actionType = $this->getActionTypeService()->getRequestedActionType($this);
        $this->getActionTypeService()->restore($actionType);
        return $this->redirect()->toRoute('unicaen-etat/etat-type');
    }

    public function supprimerAction()
    {
        $actionType = $this->getActionTypeService()->getRequestedActionType($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getActionTypeService()->delete($actionType);
            exit();
        }

        $vm = new ViewModel();
        if ($actionType !== null) {
            $vm->setTemplate('unicaen-etat/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type d'action " . $actionType->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-etat/action-type/supprimer', ["action-type" => $actionType->getId()], [], true),
            ]);
        }
        return $vm;
    }

}