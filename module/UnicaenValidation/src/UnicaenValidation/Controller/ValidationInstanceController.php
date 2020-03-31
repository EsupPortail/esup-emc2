<?php

namespace UnicaenValidation\Controller;

use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Form\ValidationInstance\ValidationInstanceFormAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ValidationInstanceController extends AbstractActionController {
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use ValidationInstanceFormAwareTrait;

    public function indexAction()
    {
        $instances = $this->getValidationInstanceService()->getValidationsInstances();

        return new ViewModel([
            'instances' => $instances,
        ]);
    }

    public function ajouterAction()
    {
        $instance = new ValidationInstance();
        $form = $this->getValidationInstancForm();
        $form->setAttribute('action', $this->url()->fromRoute('validation/instance/ajouter', [], [], true));
        $form->bind($instance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->create($instance);
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
        $instance = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $form = $this->getValidationInstancForm();
        $form->setAttribute('action', $this->url()->fromRoute('validation/instance/modifier', ['validation' => $instance->getId()], [], true));
        $form->bind($instance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->update($instance);
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
        $instance = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($instance);

        return $this->redirect()->toRoute('validation/instance');
    }

    public function restaurerAction()
    {
        $instance = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->restore($instance);

        return $this->redirect()->toRoute('validation/instance');
    }

    public function detruireAction()
    {
        $instance = $this->getValidationInstanceService()->getRequestedValidationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getValidationInstanceService()->delete($instance);
            //return $this->redirect()->toRoute('competence', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($instance !== null) {
            $vm->setTemplate('unicaen-validation/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la validation ",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('validation/instance/detruire', ["validation" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function validerAction() {
        $typeId = $this->params()->fromRoute('type');
        $type = $this->getValidationTypeService()->getValidationType($typeId);
        $entityclass = $this->params()->fromRoute('entityclass');
        $entityid = $this->params()->fromRoute('entityid');
        $retour = $this->params()->fromQuery('retour');

        $validation = new ValidationInstance();
        $validation->setType($type);
        if ($entityclass !== "none") {
            $validation->setEntityClass($entityclass);
            $validation->setEntityId($entityid);
        }
        $this->getValidationInstanceService()->create($validation);

        return $this->redirect()->toUrl($retour);
    }

    public function refuserAction() {
        $typeId = $this->params()->fromRoute('type');
        $type = $this->getValidationTypeService()->getValidationType($typeId);
        $entityclass = $this->params()->fromRoute('entityclass');
        $entityid = $this->params()->fromRoute('entityid');
        $retour = $this->params()->fromQuery('retour');

        $validation = new ValidationInstance();
        $validation->setType($type);
        $validation->setValeur("refus");
        if ($entityclass !== "none") {
            $validation->setEntityClass($entityclass);
            $validation->setEntityId($entityid);
        }
        $this->getValidationInstanceService()->create($validation);

        return $this->redirect()->toUrl($retour);
    }

}
