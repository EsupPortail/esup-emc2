<?php

namespace UnicaenContact\Controller;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenContact\Entity\Db\Contact;
use UnicaenContact\Form\Contact\ContactFormAwareTrait;
use UnicaenContact\Service\Contact\ContactServiceAwareTrait;

class ContactController extends AbstractActionController
{
    use ContactServiceAwareTrait;
    use ContactFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $contacts = $this->getContactService()->getContacts(true);

        return new ViewModel([
            'contacts' => $contacts
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $contact = $this->getContactService()->getRequestedContract($this);
        $type = $contact->getType();

        return new ViewModel([
            'contact' => $contact,
            'type' => $type,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $contact = new Contact();

        $form = $this->getContactForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-contact/contact/ajouter', [], [], true));
        $form->bind($contact);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContactService()->create($contact);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un contact",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-contact/default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $contact = $this->getContactService()->getRequestedContract($this);

        $form = $this->getContactForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-contact/contact/modifier', ['contact' => $contact->getId()], [], true));
        $form->bind($contact);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContactService()->update($contact);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un contact",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-contact/default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $contact = $this->getContactService()->getRequestedContract($this);
        $this->getContactService()->historise($contact);

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('unicaen-contact/contact');
    }

    public function restaurerAction(): Response
    {
        $contact = $this->getContactService()->getRequestedContract($this);
        $this->getContactService()->restore($contact);

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('unicaen-contact/contact');
    }

    public function supprimerAction(): ViewModel
    {
        $contact = $this->getContactService()->getRequestedContract($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getContactService()->delete($contact);
            exit();
        }

        $vm = new ViewModel();
        if ($contact !== null) {
            $vm->setTemplate('unicaen-contact/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du contact ",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-contact/contact/supprimer', ["contact" => $contact->getId()], [], true),
            ]);
        }
        return $vm;
    }

}