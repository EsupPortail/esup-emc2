<?php

namespace Application\Controller;

use Application\Entity\Db\FormationInstance;
use Application\Entity\Db\FormationInstanceInscrit;
use Application\Entity\Db\FormationInstanceJournee;
use Application\Form\FormationJournee\FormationJourneeFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceInscritServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceJourneeServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationInstanceController extends AbstractActionController {
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use FormationJourneeFormAwareTrait;
    use SelectionAgentFormAwareTrait;

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

    /** JOURNEE ********************************************************************************************/

    public function ajouterJourneeAction() {
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

    public function modifierJourneeAction() {
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

    /** INSCRIT ********************************************************************************************/

    public function ajouterAgentAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $inscrit = new FormationInstanceInscrit();
        $inscrit->setInstance($instance);

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-agent', ['formulaire-instance' => $instance->getId()], [], true));
        $form->bind($inscrit);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getFormationInstanceInscritService()->create($inscrit);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un agent pour l'instance de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $this->getFormationInstanceInscritService()->historise($inscrit);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function restaurerAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $this->getFormationInstanceInscritService()->restore($inscrit);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function supprimerAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceInscritService()->delete($inscrit);
            exit();
        }

        $vm = new ViewModel();
        if ($inscrit !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'inscription de [" . $inscrit->getAgent()->getDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-agent', ["inscrit" => $inscrit->getId()], [], true),
            ]);
        }
        return $vm;
    }

}