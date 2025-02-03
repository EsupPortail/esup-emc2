<?php

namespace UnicaenContact\Controller;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenContact\Entity\Db\Type;
use UnicaenContact\Form\Type\TypeFormAwareTrait;
use UnicaenContact\Service\Contact\ContactServiceAwareTrait;
use UnicaenContact\Service\Type\TypeServiceAwareTrait;

class TypeController extends AbstractActionController
{
    use ContactServiceAwareTrait;
    use TypeServiceAwareTrait;
    use TypeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $types = $this->getTypeService()->getTypes(true);

        return new ViewModel([
            'types' => $types,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $type = $this->getTypeService()->getRequestedType($this);
        $contacts = $this->getContactService()->getContactsByType($type);

        return new ViewModel([
            'type' => $type,
            'contacts' => $contacts,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $type = new Type();

        $form = $this->getTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-contact/type/ajouter', [], [], true));
        $form->bind($type);
        $form->setOldCode("");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTypeService()->create($type);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un type de contact",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-contact/default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $type = $this->getTypeService()->getRequestedType($this);

        $form = $this->getTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-contact/type/modifier', ['contact-type' => $type->getId()], [], true));
        $form->bind($type);
        $form->setOldCode($type->getCode());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTypeService()->update($type);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du type de contact [".$type->getCode()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-contact/default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $type = $this->getTypeService()->getRequestedType($this);
        $this->getTypeService()->historise($type);

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('unicaen-contact/type');
    }

    public function restaurerAction(): Response
    {
        $type = $this->getTypeService()->getRequestedType($this);
        $this->getTypeService()->restore($type);

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('unicaen-contact/type');
    }

    public function supprimerAction(): ViewModel
    {
        $type = $this->getTypeService()->getRequestedType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getTypeService()->delete($type);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('unicaen-contact/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type de contact " . $type->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-contact/type/supprimer', ["contact-type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }
}