<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\Mobilite;
use Carriere\Form\Mobilite\MobiliteFormAwareTrait;
use Carriere\Service\Mobilite\MobiliteServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class MobiliteController extends AbstractActionController
{
    use MobiliteServiceAwareTrait;
    use MobiliteFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $mobilites = $this->getMobiliteService()->getMobilites();

        return new ViewModel([
            'mobilites' => $mobilites,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $mobilite = $this->getMobiliteService()->getRequestedMobilite($this);

        return new ViewModel([
            'title' => "Afficher de la mobilite [".$mobilite->getCode()."]",
            'mobilite' => $mobilite,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $mobilite = new Mobilite();

        $form = $this->getMobiliteForm();
        $form->setAttribute('action', $this->url()->fromRoute('mobilite/ajouter', [], [], true));
        $form->bind($mobilite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMobiliteService()->create($mobilite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une mobilité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $mobilite = $this->getMobiliteService()->getRequestedMobilite($this);

        $form = $this->getMobiliteForm();
        $form->setAttribute('action', $this->url()->fromRoute('mobilite/modifier', ['mobilite' => $mobilite->getId()], [], true));
        $form->bind($mobilite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMobiliteService()->update($mobilite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une mobilité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $mobilite = $this->getMobiliteService()->getRequestedMobilite($this);
        $this->getMobiliteService()->historise($mobilite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mobilite', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $mobilite = $this->getMobiliteService()->getRequestedMobilite($this);
        $this->getMobiliteService()->restore($mobilite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mobilite', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $mobilite = $this->getMobiliteService()->getRequestedMobilite($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMobiliteService()->delete($mobilite);
            exit();
        }

        $vm = new ViewModel();
        if ($mobilite !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mobilité " . $mobilite->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mobilite/supprimer', ["mobilite" => $mobilite->getId()], [], true),
            ]);
        }
        return $vm;
    }
}