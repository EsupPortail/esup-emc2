<?php

namespace Mailing\Controller;

use Mailing\Form\MailContent\MailContentFormAwareTrait;
use Mailing\Form\MailType\MailTypeFormAwareTrait;
use Mailing\Model\Db\MailType;
use Mailing\Service\MailType\MailTypeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MailTypeController extends AbstractActionController {
    use MailTypeServiceAwareTrait;
    use MailTypeFormAwareTrait;
    use MailContentFormAwareTrait;

    public function afficherAction()
    {
        $type = $this->getMailTypeService()->getRequestedMailType($this);

        return new ViewModel([
            'title' => "Affichage d'un mail type",
            'type' => $type,
        ]);
    }

    public function ajouterAction()
    {
        $type = new MailType();
        $form = $this->getMailTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('mailing/type/ajouter', [], [], true));
        $form->bind($type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMailTypeService()->create($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('mailing/default/default-form');
        $vm->setVariables([
            'title' => "Ajout des informations d'un mail type",
            'form' => $form,
        ]);
        return $vm;
    }
    
    public function modifierAction() 
    {
        $type = $this->getMailTypeService()->getRequestedMailType($this);
        $form = $this->getMailTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('mailing/type/modifier', ['mail-type' => $type->getId()], [], true));
        $form->bind($type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMailTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('mailing/default/default-form');
        $vm->setVariables([
            'title' => "Modification des informations d'un mail type",
           'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $type = $this->getMailTypeService()->getRequestedMailType($this);
        $this->getMailTypeService()->historise($type);
        return $this->redirect()->toRoute('mailing', [], ["fragment" => "type"]);
        
    }
    
    public function restaurerAction() 
    {
        $type = $this->getMailTypeService()->getRequestedMailType($this);
        $this->getMailTypeService()->restore($type);
        return $this->redirect()->toRoute('mailing', [], ["fragment" => "type"]);
    }

    public function detruireAction()
    {
        $type = $this->getMailTypeService()->getRequestedMailType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getMailTypeService()->delete($type);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('mailing/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du mail type",
                'text' => "La suppression du mail type est définitive. <br/>Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mailing/type/detruire', ["type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierMailAction()
    {
        $type = $this->getMailTypeService()->getRequestedMailType($this);
        $form = $this->getMailContentForm();
        $form->setAttribute('action', $this->url()->fromRoute('mailing/type/modifier-mail', ['mail-type' => $type->getId()], [], true));
        $form->bind($type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMailTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => "Modification du conteny d'un mail type",
            'form' => $form,
        ]);
        return $vm;
        
    }
}