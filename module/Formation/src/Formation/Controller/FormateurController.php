<?php

namespace Formation\Controller;

use Formation\Entity\Db\Formateur;
use Formation\Form\Formateur\FormateurFormAwareTrait;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FormateurController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormateurServiceAwareTrait;
    use FormateurFormAwareTrait;
    use FormateurFormAwareTrait;

    public function ajouterFormateurAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $formateur = new Formateur();
        $formateur->setInstance($instance);

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-formateur', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->create($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Ajout d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormateurAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-formateur', ['formateur' => $formateur->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->update($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Modification d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserFormateurAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->historise($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function restaurerFormateurAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->restore($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function supprimerFormateurAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormateurService()->delete($formateur);
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