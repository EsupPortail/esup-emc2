<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\Observateur;
use EntretienProfessionnel\Form\Observateur\ObservateurFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Observateur\ObservateurServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ObservateurController extends AbstractActionController {
    use EntretienProfessionnelServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use ObservateurFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $observateurs = $this->getObservateurService()->getObservateurs(true);
        return new ViewModel([
            'observateurs' => $observateurs,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $observateur = new Observateur();
        $observateur->setEntretienProfessionnel($entretien);
        $form = $this->getObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observateur/ajouter', ['entretien-professionnel' => $entretien->getId()], [], true));
        $form->bind($observateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservateurService()->create($observateur);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un·e observateur·trice pour l'entretien professionnel de ". $entretien->getAgent()->getDenomination(true),
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        $form = $this->getObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observateur/modifier', ['observateur' => $observateur->getId()], [], true));
        $form->bind($observateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservateurService()->update($observateur);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de l'observateur·trice pour l'entretien professionnel de ". $observateur->getEntretienProfessionnel()->getAgent()->getDenomination(true),
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);
        $this->getObservateurService()->historise($observateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $observateur->getEntretienProfessionnel()->getId()], [], true);
    }

    public function restaurerAction(): Response
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);
        $this->getObservateurService()->restore($observateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $observateur->getEntretienProfessionnel()->getId()], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getObservateurService()->delete($observateur);
            exit();
        }

        $vm = new ViewModel();
        if ($observateur !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'obervateur·trice " . $observateur->getUser()->getDisplayName(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/observateur/supprimer', ["observateur" => $observateur->getId()], [], true),
            ]);
        }
        return $vm;
    }
}