<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationInstanceJournee;
use Formation\Form\FormationJournee\FormationJourneeFormAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationInstanceJourneeController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use FormationJourneeFormAwareTrait;

    public function ajouterJourneeAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $journee = new FormationInstanceJournee();
        $journee->setInstance($instance);

        $form = $this->getFormationJourneeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-journee', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceJourneeService()->create($journee);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);

        $form = $this->getFormationJourneeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-journee', ['journee' => $journee->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceJourneeService()->update($journee);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);
        $this->getFormationInstanceJourneeService()->historise($journee);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $journee->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);
        $this->getFormationInstanceJourneeService()->restore($journee);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $journee->getInstance()->getId()], [], true);
    }

    public function supprimerJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceJourneeService()->delete($journee);
            exit();
        }

        $vm = new ViewModel();
        if ($journee !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la journée de formation du [" . $journee->getJour() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-journee', ["journee" => $journee->getId()], [], true),
            ]);
        }
        return $vm;
    }
}