<?php

namespace Application\Controller;

use Application\Entity\Db\FormationInstance;
use Application\Form\FormationJournee\FormationJourneeFormAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationInstanceController extends AbstractActionController {
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationJourneeFormAwareTrait;

    public function ajouterAction() {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $instance = new FormationInstance();
        $instance->setFormation($formation);

        $this->getFormationInstanceService()->create($instance);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function afficherAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        //todo une seule vue parametrée par les privilèges || mode
        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
        ]);
    }

    public function modifierAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        //todo une seule vue parametrée par les privilèges || mode
        return new ViewModel([
            'instance' => $instance,
            'mode' => "modification",
        ]);
    }

    public function historiserAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->historise($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function restaurerAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->restore($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function supprimerAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceService()->delete($instance);
            exit();
        }

        $vm = new ViewModel();
        if ($instance !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une instance de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer', ["formation-instance" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function ajouterJourneeAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $form = $this->getFormationJourneeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-journee', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($instance);

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
           'title' => "Ajout d'une journée de formation",
           'form' => $form,
        ]);
        return $vm;
    }
}