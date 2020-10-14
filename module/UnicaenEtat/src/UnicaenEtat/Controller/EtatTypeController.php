<?php

namespace UnicaenEtat\Controller;

use UnicaenEtat\Entity\Db\EtatType;
use UnicaenEtat\Form\EtatType\EtatTypeFormAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EtatTypeController extends AbstractActionController
{
    use EtatTypeServiceAwareTrait;
    use EtatTypeFormAwareTrait;

    public function indexAction()
    {
        $etatTypes = $this->getEtatTypeService()->getEtatTypes();
        return new ViewModel([
            'etatTypes' => $etatTypes,
        ]);
    }

    public function ajouterAction()
    {
        $etatType = new EtatType();

        $form = $this->getEtatTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/etat-type/ajouter', [], [], true));
        $form->bind($etatType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatTypeService()->create($etatType);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-etat/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type d'état",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $etatType = $this->getEtatTypeService()->getRequestedEtatType($this);

        $form = $this->getEtatTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/etat-type/modifier', ['etat-type' => $etatType->getId()], [], true));
        $form->bind($etatType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatTypeService()->update($etatType);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-etat/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type d'état",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $etatType = $this->getEtatTypeService()->getRequestedEtatType($this);
        $this->getEtatTypeService()->historise($etatType);
        return $this->redirect()->toRoute('unicaen-etat/etat-type');
    }

    public function restaurerAction()
    {
        $etatType = $this->getEtatTypeService()->getRequestedEtatType($this);
        $this->getEtatTypeService()->restore($etatType);
        return $this->redirect()->toRoute('unicaen-etat/etat-type');
    }

    public function supprimerAction()
    {
        $etatType = $this->getEtatTypeService()->getRequestedEtatType($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getEtatTypeService()->delete($etatType);
            exit();
        }

        $vm = new ViewModel();
        if ($etatType !== null) {
            $vm->setTemplate('unicaen-etat/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type d'état " . $etatType->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-etat/etat-type/supprimer', ["etat-type" => $etatType->getId()], [], true),
            ]);
        }
        return $vm;
    }

}