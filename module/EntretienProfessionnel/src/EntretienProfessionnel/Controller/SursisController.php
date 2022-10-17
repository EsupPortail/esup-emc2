<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\Sursis;
use EntretienProfessionnel\Form\Sursis\SursisFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Sursis\SursisServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SursisController extends AbstractActionController {
    use EntretienProfessionnelServiceAwareTrait;
    use SursisServiceAwareTrait;
    use SursisFormAwareTrait;

    public function afficherAction() : ViewModel
    {
        $sursis = $this->getSursisService()->getRequestedSursis($this);
        return new ViewModel([
            'title' => "Affichage du sursis",
            'sursis' => $sursis,
        ]);
    }
    
    public function ajouterAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        
        $sursis = new Sursis();
        $sursis->setEntretien($entretien);
        
        $form = $this->getSursisForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/sursis/ajouter', ['entretien-professionnel' => $entretien->getId()], [], true));
        $form->bind($sursis);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSursisService()->create($sursis);
            }
        }
        
        $vm = new ViewModel([
           'title' => "Ajout d'un sursis",
           'form' => $form,
        ]);
        $vm->setTemplate('entretien-professionnel/default/default-form');
        return $vm;
    }
    
    public function modifierAction()  : ViewModel
    {
        $sursis = $this->getSursisService()->getRequestedSursis($this);
        
        $form = $this->getSursisForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/sursis/modifier', ['sursis' => $sursis->getId()], [], true));
        $form->bind($sursis);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSursisService()->update($sursis);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un sursis",
            'form' => $form,
        ]);
        $vm->setTemplate('entretien-professionnel/default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $sursis = $this->getSursisService()->getRequestedSursis($this);
        $retour = $this->params()->fromQuery('retour');
        $this->getSursisService()->historise($sursis);
        return $this->redirect()->toUrl($retour);
    }

    public function restaurerAction() : Response
    {
        $sursis = $this->getSursisService()->getRequestedSursis($this);
        $retour = $this->params()->fromQuery('retour');
        $this->getSursisService()->restore($sursis);
        return $this->redirect()->toUrl($retour);
    }

    public function supprimerAction() : ViewModel
    {
        $sursis = $this->getSursisService()->getRequestedSursis($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getSursisService()->delete($sursis);
            exit();
        }

        $vm = new ViewModel();
        if ($sursis !== null) {
            $vm->setTemplate('entretien-professionnel/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'un sursis",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/sursis/supprimer', ["sursis" => $sursis->getId()], [], true),
            ]);
        }
        return $vm;
    }
}