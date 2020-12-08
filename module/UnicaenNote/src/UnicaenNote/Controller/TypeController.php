<?php

namespace UnicaenNote\Controller;

use UnicaenNote\Entity\Db\Type;
use UnicaenNote\Form\Type\TypeFormAwareTrait;
use UnicaenNote\Service\Type\TypeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TypeController extends AbstractActionController {
    use TypeServiceAwareTrait;
    use TypeFormAwareTrait;

    public function indexAction()
    {
        $types = $this->getTypeService()->getTypes();

        return new ViewModel([
            'types' => $types,
        ]);
    }

    public function afficherAction()
    {
        $type = $this->getTypeService()->getRequestedType($this);
        return new ViewModel([
            'title' => "Affichage d'un type de note",
            'type' => $type,
        ]);
    }

    public function ajouterAction()
    {
        $type = new Type();

        $form = $this->getTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-note/type/ajouter', [], [], true));
        $form->bind($type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTypeService()->create($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-note/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de note",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $type = $this->getTypeService()->getRequestedType($this);

        $form = $this->getTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-note/type/modifier', ['type' => $type->getId()], [], true));
        $form->bind($type);
        $form->setOldCode($type->getCode());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-note/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de note",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $type = $this->getTypeService()->getRequestedType($this);
        $this->getTypeService()->historise($type);
        return $this->redirect()->toRoute('unicaen-note/type', [], [], true);
    }

    public function restaurerAction()
    {
        $type = $this->getTypeService()->getRequestedType($this);
        $this->getTypeService()->restore($type);
        return $this->redirect()->toRoute('unicaen-note/type', [], [], true);
    }

    public function supprimerAction()
    {
        $type = $this->getTypeService()->getRequestedType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getTypeService()->delete($type);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('unicaen-note/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type",
                'text' => "La suppression du type est définitive. <br/>Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-note/type/supprimer', ["type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }
}