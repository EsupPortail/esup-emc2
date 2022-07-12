<?php

namespace UnicaenValidation\Controller;

use UnicaenValidation\Entity\Db\ValidationType;
use UnicaenValidation\Form\ValidationType\ValidationTypeFormAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ValidationTypeController extends AbstractActionController
{
    use ValidationTypeServiceAwareTrait;
    use ValidationTypeFormAwareTrait;

    public function indexAction()
    {
        $types = $this->getValidationTypeService()->getValidationsTypes();

        return new ViewModel([
            'types' => $types,
        ]);
    }

    public function ajouterAction()
    {
        $type = new ValidationType();
        $form = $this->getValidationTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('validation/type/ajouter', [], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationTypeService()->create($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de validation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $type = $this->getValidationTypeService()->getRequestedValidationType($this);
        $form = $this->getValidationTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('validation/type/modifier', ['type' => $type->getId()], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de validation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $type = $this->getValidationTypeService()->getRequestedValidationType($this);
        $this->getValidationTypeService()->historise($type);

        return $this->redirect()->toRoute('validation/type');
    }

    public function restaurerAction()
    {
        $type = $this->getValidationTypeService()->getRequestedValidationType($this);
        $this->getValidationTypeService()->restore($type);

        return $this->redirect()->toRoute('validation/type');
    }

    public function detruireAction()
    {
        $type = $this->getValidationTypeService()->getRequestedValidationType($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getValidationTypeService()->delete($type);
            //return $this->redirect()->toRoute('competence', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('unicaen-validation/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type de validation " . $type->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('validation/type/detruire', ["type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }

}