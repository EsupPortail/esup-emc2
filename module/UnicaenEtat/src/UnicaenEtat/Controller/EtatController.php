<?php

namespace UnicaenEtat\Controller;

use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Form\Etat\EtatFormAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class EtatController extends AbstractActionController
{
    use EtatServiceAwareTrait;
    use EtatFormAwareTrait;

    public function indexAction()
    {
        $etats = $this->getEtatService()->getEtats();
        return new ViewModel([
            'etats' => $etats,
        ]);
    }

    public function ajouterAction()
    {
        $etat = new Etat();

        $form = $this->getEtatForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/etat/ajouter', [], [], true));
        $form->bind($etat);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatService()->create($etat);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-etat/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un état",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $etat = $this->getEtatService()->getRequestedEtat($this);

        $form = $this->getEtatForm();
        $form->setAttribute('action', $this->url()->fromRoute('unicaen-etat/etat/modifier', ['etat' => $etat->getId()], [], true));
        $form->bind($etat);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatService()->update($etat);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-etat/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un état",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $etat = $this->getEtatService()->getRequestedEtat($this);
        $this->getEtatService()->historise($etat);
        return $this->redirect()->toRoute('unicaen-etat/etat');
    }

    public function restaurerAction()
    {
        $etat = $this->getEtatService()->getRequestedEtat($this);
        $this->getEtatService()->restore($etat);
        return $this->redirect()->toRoute('unicaen-etat/etat');
    }

    public function supprimerAction()
    {
        $etat = $this->getEtatService()->getRequestedEtat($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getEtatService()->delete($etat);
            exit();
        }

        $vm = new ViewModel();
        if ($etat !== null) {
            $vm->setTemplate('unicaen-etat/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'état " . $etat->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('unicaen-etat/etat/supprimer', ["etat-type" => $etat->getId()], [], true),
            ]);
        }
        return $vm;
    }

}