<?php

namespace Metier\Controller;

use Metier\Entity\Db\Reference;
use Metier\Form\Reference\ReferenceFormAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Reference\ReferenceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ReferenceController extends AbstractActionController {
    use MetierServiceAwareTrait;
    use ReferenceServiceAwareTrait;
    use ReferenceFormAwareTrait;

    public function ajouterAction()  :ViewModel
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);
        $reference = new Reference();
        $reference->setMetier($metier);
        $form = $this->getReferenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/reference/ajouter', [], [], true));
        $form->bind($reference);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferenceService()->create($reference);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une référence",
            'form' => $form,
        ]);
        $vm->setTemplate('metier/default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $reference = $this->getReferenceService()->getRequestedReference($this);
        $form = $this->getReferenceForm();
        /** @see ReferenceController::modifierAction() */
        $form->setAttribute('action', $this->url()->fromRoute('metier/reference/modifier', ['reference' => $reference->getId()], [], true));
        $form->bind($reference);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferenceService()->update($reference);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'une référence métier",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $reference = $this->getReferenceService()->getRequestedReference($this);
        $this->getReferenceService()->historise($reference);
        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function restaurerAction() : Response
    {
        $reference = $this->getReferenceService()->getRequestedReference($this);
        $this->getReferenceService()->restore($reference);
        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function supprimerAction() : ViewModel
    {
        $reference = $this->getReferenceService()->getRequestedReference($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getReferenceService()->delete($reference);
            exit();
        }

        $vm = new ViewModel();
        if ($reference !== null) {
            $vm->setTemplate('metier/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la référence " . $reference->getTitre(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('metier/reference/supprimer', ["reference" => $reference->getId()], [], true),
            ]);
        }
        return $vm;
    }

}