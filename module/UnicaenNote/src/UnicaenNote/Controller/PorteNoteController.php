<?php

namespace UnicaenNote\Controller;

use UnicaenNote\Entity\Db\PorteNote;
use UnicaenNote\Form\PorteNote\PorteNoteFormAwareTrait;
use UnicaenNote\Service\PorteNote\PorteNoteServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PorteNoteController extends AbstractActionController {
    use PorteNoteServiceAwareTrait;
    use PorteNoteFormAwareTrait;

    public function indexAction()
    {
        $portesnotes = $this->getPorteNoteService()->getPortesNotes();
        return new ViewModel([
           'portesnotes' => $portesnotes,
        ]);
    }

    public function afficherAction()
    {
        $porteNote = $this->getPorteNoteService()->getRequestePorteNote($this);
        return new ViewModel([
            'title' => "Affichage d'un porte-notes",
            'portenote' => $porteNote,
        ]);
    }

    public function ajouterAction()
    {
        $porteNote = new PorteNote();
        $form = $this->getPorteNoteForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-note/porte-note/ajouter', [], [], true));
        $form->bind($porteNote);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPorteNoteService()->create($porteNote);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-note/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un prote-note",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $porteNote = $this->getPorteNoteService()->getRequestePorteNote($this);
        $form = $this->getPorteNoteForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-note/porte-note/modifier', ['porte-note' => $porteNote->getId()], [], true));
        $form->bind($porteNote);
        $form->setOldLibelle($porteNote->getAccroche());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPorteNoteService()->update($porteNote);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-note/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un prote-note",
            'form' => $form,
        ]);
        return $vm;
    }

    //ordonnerNotesAction

    public function historiserAction()
    {
        $porteNote = $this->getPorteNoteService()->getRequestePorteNote($this);
        $this->getPorteNoteService()->historise($porteNote);
        return $this->redirect()->toRoute('unicaen-note/porte-note', [], [], true);
    }

    public function restaurerAction()
    {
        $porteNote = $this->getPorteNoteService()->getRequestePorteNote($this);
        $this->getPorteNoteService()->restore($porteNote);
        return $this->redirect()->toRoute('unicaen-note/porte-note', [], [], true);
    }

    public function supprimerAction()
    {
        $porteNote = $this->getPorteNoteService()->getRequestePorteNote($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getPorteNoteService()->delete($porteNote);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($porteNote !== null) {
            $vm->setTemplate('unicaen-note/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du porte-notes",
                'text' => "La suppression du porte-notes est définitive. <br/>Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-note/porte-note/supprimer', ["porte-note" => $porteNote->getId()], [], true),
            ]);
        }
        return $vm;
    }
}