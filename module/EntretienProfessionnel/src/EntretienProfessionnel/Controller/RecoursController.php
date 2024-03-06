<?php

namespace EntretienProfessionnel\Controller;

use DateTime;
use EntretienProfessionnel\Entity\Db\Recours;
use EntretienProfessionnel\Form\Recours\RecoursFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Recours\RecoursServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RecoursController extends AbstractActionController
{
    use EntretienProfessionnelServiceAwareTrait;
    use RecoursServiceAwareTrait;
    use RecoursFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $recours = new Recours();
        $recours->setEntretien($entretien);
        $recours->setDateProcedure(new DateTime());
        $recours->setEntretienModifiable(true);

        $form = $this->getRecoursForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/recours/ajouter', ['entretien-professionnel' => $entretien->getId()], [], true));
        $form->bind($recours);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRecoursService()->create($recours);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une procédure de recours",
            'form' => $form,
            'js' => null,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $recours = $this->getRecoursService()->getRequestedRecours($this);
        $form = $this->getRecoursForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/recours/modifier', ['recours' => $recours->getId()], [], true));
        $form->bind($recours);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRecoursService()->update($recours);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'une procédure de recours",
            'form' => $form,
            'js' => null,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function toggleAction() : Response
    {
        $recours = $this->getRecoursService()->getRequestedRecours($this);
        $recours->setEntretienModifiable(!$recours->isEntretienModifiable());
        $this->getRecoursService()->update($recours);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $recours->getEntretien()->getId()], ['fragment' => 'validation'], true);
    }

    public function historiserAction() : Response
    {
        $recours = $this->getRecoursService()->getRequestedRecours($this);
        $this->getRecoursService()->historise($recours);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $recours->getEntretien()->getId()], ['fragment' => 'validation'], true);
    }

    public function restaurerAction() : Response
    {
        $recours = $this->getRecoursService()->getRequestedRecours($this);
        $this->getRecoursService()->restore($recours);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $recours->getEntretien()->getId()], ['fragment' => 'validation'], true);
    }

    public function supprimerAction() : ViewModel
    {
        $recours = $this->getRecoursService()->getRequestedRecours($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getRecoursService()->delete($recours);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($recours !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la procédure de recours",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/recours/supprimer', ["recours" => $recours->getId()], [], true),
            ]);
        }
        return $vm;
    }
}