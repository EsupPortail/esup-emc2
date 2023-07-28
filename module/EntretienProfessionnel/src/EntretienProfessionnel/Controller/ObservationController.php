<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\Observation;
use EntretienProfessionnel\Form\Observation\ObservationFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Observation\ObservationServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

class ObservationController extends AbstractActionController {
    use EntretienProfessionnelServiceAwareTrait;
    use EtatServiceAwareTrait;
    use ObservationServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ObservationFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $observation = new Observation();
        $observation->setEntretien($entretien);

        $form = $this->getObservationForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observation/ajouter', ['entretien-professionnel' => $entretien->getId()], [], true));
        $form->bind($observation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationService()->create($observation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('entretien-professionnel/entretien-professionnel/observation-form');
        $vm->setVariables([
            'title' => "Ajout d'une observation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);

        $form = $this->getObservationForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observation/modifier', ['observation' => $observation->getId()], [], true));
        $form->bind($observation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationService()->update($observation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('entretien-professionnel/entretien-professionnel/observation-form');
        $vm->setVariables([
            'title' => "Modification d'une observation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);
        $this->getObservationService()->historise($observation);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $observation->getEntretien()->getId()], ['fragment' => 'avis'], true);
    }

    public function restaurerAction() : Response
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);
        $this->getObservationService()->restore($observation);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $observation->getEntretien()->getId()], ['fragment' => 'avis'], true);
    }

    public function supprimerAction() : ViewModel
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getObservationService()->delete($observation);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($observation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'observation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/observation/supprimer', ["observation" => $observation->getId()], [], true),
            ]);
        }
        return $vm;
    }
}