<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\Observation;
use EntretienProfessionnel\Form\Observation\ObservationFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Observation\ObservationServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ObservationController extends AbstractActionController {
    use EntretienProfessionnelServiceAwareTrait;
    use MailingServiceAwareTrait;
    use ObservationServiceAwareTrait;
    use ObservationFormAwareTrait;

    public function ajouterAction()
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
                $mail = $this->getMailingService()->sendMailType("NOTIFICATION_OBSERVATION_AGENT", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'mailing' => $entretien->getResponsable()->getEmail()]);
                $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
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

    public function modifierAction()
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

    public function historiserAction()
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);
        $this->getObservationService()->historise($observation);
        return $this->redirect()->toRoute('entretien-professionnel/renseigner', ['entretien-professionnel' => $observation->getEntretien()->getId()], ['fragment' => 'avis'], true);
    }

    public function restaurerAction()
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);
        $this->getObservationService()->restore($observation);
        return $this->redirect()->toRoute('entretien-professionnel/renseigner', ['entretien-professionnel' => $observation->getEntretien()->getId()], ['fragment' => 'avis'], true);
    }

    public function supprimerAction()
    {
        $observation = $this->getObservationService()->getRequestedObservation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getObservationService()->delete($observation);
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