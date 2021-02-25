<?php

namespace UnicaenNote\Controller;

use UnicaenNote\Entity\Db\Note;
use UnicaenNote\Form\Note\NoteFormAwareTrait;
use UnicaenNote\Service\Note\NoteServiceAwareTrait;
use UnicaenNote\Service\PorteNote\PorteNoteServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoteController extends AbstractActionController {
    use NoteServiceAwareTrait;
    use PorteNoteServiceAwareTrait;
    use NoteFormAwareTrait;

    public function indexAction()
    {
        $notes = $this->getNoteService()->getNotes();

        return new ViewModel([
            'notes' => $notes,
        ]);
    }

    public function afficherAction()
    {
        $note = $this->getNoteService()->getRequestedNote($this);

        return new ViewModel([
            'note' => $note,
        ]);
    }

    public function ajouterAction()
    {
        $portenote = $this->getPorteNoteService()->getRequestePorteNote($this);
        $note = new Note();
        if ($portenote) {
            $note->setPortenote($portenote);
        }

        $form = $this->getNoteForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-note/note/ajouter', ['porte-note' => (($portenote)?$portenote->getId():null)], [], true));
        $form->bind($note);
//        if ($portenote !== null) {
//            $form->get('porte-note')->setAttribute('style','display:none;');
//        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNoteService()->create($note);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-note/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un note",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $note = $this->getNoteService()->getRequestedNote($this);

        $form = $this->getNoteForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-note/note/modifier', ['note' => $note->getId()], [], true));
        $form->bind($note);
        //$form->get('porte-note')->setAttribute('style','display:none;');

        $request = $this->getRequest();
        if ($request->getPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNoteService()->update($note);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-note/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un note",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $note = $this->getNoteService()->getRequestedNote($this);
        $this->getNoteService()->historise($note);
        return $this->redirect()->toRoute('unicaen-note/note', [], [], true);
    }

    public function restaurerAction()
    {
        $note = $this->getNoteService()->getRequestedNote($this);
        $this->getNoteService()->restore($note);
        return $this->redirect()->toRoute('unicaen-note/note', [], [], true);
    }

    public function supprimerAction()
    {
        $note = $this->getNoteService()->getRequestedNote($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getNoteService()->delete($note);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($note !== null) {
            $vm->setTemplate('unicaen-note/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une note",
                'text' => "La suppression d'une note est définitive. <br/>Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-note/note/supprimer', ["note" => $note->getId()], [], true),
            ]);
        }
        return $vm;
    }

}