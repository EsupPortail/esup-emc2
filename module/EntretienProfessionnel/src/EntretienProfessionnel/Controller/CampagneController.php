<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class CampagneController extends AbstractActionController {
    use CampagneServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use RappelCampagneAvancementServiceAwareTrait;
    use CampagneFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $campagnes = $this->getCampagneService()->getCampagnes();

        return new ViewModel([
            'campagnes' => $campagnes,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $campagne = new Campagne();
        $campagne->setAnnee(CampagneService::getAnneeScolaire());

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/ajouter', [], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->create($campagne);
                $this->flashMessenger()->addSuccessMessage("La campagne d'entretien professionnel [".$campagne->getAnnee()."] vient d'être créée.");

               $this->getRappelCampagneAvancementService()->creer($campagne);
               $this->getNotificationService()->triggerCampagneOuverturePersonnels($campagne);
               $this->getNotificationService()->triggerCampagneOuvertureDirections($campagne);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/modifier', ['campagne' => $campagne->getId()], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->update($campagne);
                $this->flashMessenger()->addSuccessMessage("La campagne d'entretien professionnel [".$campagne->getAnnee()."] vient d'être modifiée.");
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->historise($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function restaurerAction() : Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->restore($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function detruireAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCampagneService()->delete($campagne);
            exit();
        }

        $vm = new ViewModel();
        if ($campagne !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la campagne " . $campagne->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/detruire', ["campagne" => $campagne->getId()], [], true),
            ]);
        }
        return $vm;
    }
}