<?php

namespace Structure\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenContact\Entity\Db\Contact;
use UnicaenContact\Form\Contact\ContactFormAwareTrait;
use UnicaenContact\Service\Contact\ContactServiceAwareTrait;

class ContactController extends AbstractActionController
{
    use ContactServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContactFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $contact = new Contact();

        $form = $this->getContactForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/contact/ajouter', ['structure' => $structure->getId()], [], true));
        $form->bind($contact);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContactService()->create($contact);
                $structure->addContact($contact);
                $this->getStructureService()->update($structure);
                exit();
            }
        }

        $vm = new ViewModel([
           'title' => "Ajout d'un contact pour la structure ".$structure->getLibelleLong(),
           'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

}

