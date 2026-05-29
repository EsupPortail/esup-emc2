<?php

namespace Structure\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Form\Contact\ContactFormAwareTrait;
use UnicaenContact\Entity\Db\Contact;
use UnicaenContact\Service\Contact\ContactServiceAwareTrait;
use UnicaenContact\Service\Type\TypeServiceAwareTrait;

class ContactController extends AbstractActionController
{
    use ContactServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TypeServiceAwareTrait;
    use ContactFormAwareTrait;


    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $typeId = (isset($params['type']) AND $params['type'] !== '') ? $params['type'] : null;
        $type = $this->getTypeService()->getType($typeId);

        $structureId = (isset($params['structure']) AND $params['structure'] !== '') ? $params['structure'] : null;
        $structure = $this->getStructureService()->getStructure($structureId);

        $structures = $this->getStructureService()->getStructuresWithContacts();
        if ($type === null) {
            $contacts = $this->getContactService()->getContacts();
        } else {
            $contacts = $this->getContactService()->getContactsByType($type);
        }

        $dictionnaire = [];
        foreach ($structures as $structure_) {
            $contacts_ = $structure_->getContacts();
            foreach ($contacts_ as $contact) {
                $dictionnaire[$contact->getId()][] = $structure_;
            }
        }

        if ($structureId !== null) {
            if ($structureId == -1) {
                $contacts = array_filter($contacts, function (Contact $contact) use ($dictionnaire) { return !isset($dictionnaire[$contact->getId()]); });
            } else {
                $contacts = array_filter($contacts, function (Contact $contact) use ($dictionnaire, $structure) { return in_array($structure,$dictionnaire[$contact->getId()]??[]); });
            }
        }

        return new ViewModel([
            'contacts' => $contacts,
            'dictionnaire' => $dictionnaire,

            'structures' => $this->getStructureService()->getStructuresAsOptionGroup(),
            'types' => $this->getTypeService()->getTypes(),
            'params' => $params,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $contact = new Contact();

        $form = $this->getContactForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/contact/ajouter', ['structure' => $structure?->getId()], [], true));
        $form->bind($contact);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContactService()->create($contact);

                if ($structure) {
                    $structure->addContact($contact);
                    $this->getStructureService()->update($structure);
                } else {
                    //handle the structure
                    $structures = $contact->getTmp();
                    foreach ($structures as $structure) $this->getStructureService()->update($structure);
                }
                exit();
            }
        }

        $vm = new ViewModel([
           'title' => "Ajout d'un contact" . (($structure)?("pour la structure ".$structure->getLibelleLong()):""),
           'form' => $form,
        ]);
        $vm->setTemplate('structure/contact/formulaire');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $contact = $this->getContactService()->getRequestedContract($this);

        $form = $this->getContactForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/contact/modifier', ['contact' => $contact->getId()], [], true));
        $form->bind($contact);

        $request  = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContactService()->update($contact);

                //handle the structure
                $structures = $contact->getTmp();
                foreach ($structures as $structure) $this->getStructureService()->update($structure);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un contact",
            'form' => $form,
        ]);
        $vm->setTemplate('structure/contact/formulaire');
        return $vm;
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
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'un contact",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/contact/supprimer', ["contact" => $contact->getId()], [], true),
            ]);
        }
        return $vm;
    }

}

