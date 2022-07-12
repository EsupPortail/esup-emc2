<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationInstanceFormateur;
use Formation\Form\FormationInstanceFormateur\FormationInstanceFormateurFormAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceFormateur\FormationInstanceFormateurServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FormationInstanceFormateurController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceFormateurServiceAwareTrait;
    use FormationInstanceFormateurFormAwareTrait;

    public function ajouterFormateurAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $formateur = new FormationInstanceFormateur();
        $formateur->setInstance($instance);

        $form = $this->getFormationInstanceFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-formateur', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceFormateurService()->create($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormateurAction() : ViewModel
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);

        $form = $this->getFormationInstanceFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-formateur', ['formateur' => $formateur->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceFormateurService()->update($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserFormateurAction() : Response
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);
        $this->getFormationInstanceFormateurService()->historise($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function restaurerFormateurAction() : Response
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);
        $this->getFormationInstanceFormateurService()->restore($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function supprimerFormateurAction() : ViewModel
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceFormateurService()->delete($formateur);
            exit();
        }

        $vm = new ViewModel();
        if ($formateur !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du formateur de formation du [" . $formateur->getPrenom() . " " . $formateur->getNom() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-formateur', ["formateur" => $formateur->getId()], [], true),
            ]);
        }
        return $vm;
    }
}