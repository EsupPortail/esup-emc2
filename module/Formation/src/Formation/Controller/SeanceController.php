<?php

namespace Formation\Controller;

use Formation\Entity\Db\Seance;
use Formation\Event\DemandeRetour\DemandeRetourEventAwareTrait;
use Formation\Event\InscriptionCloture\InscriptionClotureEventAwareTrait;
use Formation\Form\Seance\SeanceFormAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class SeanceController extends AbstractActionController
{
    use SeanceServiceAwareTrait;
    use SessionServiceAwareTrait;

    use InscriptionClotureEventAwareTrait;
    use DemandeRetourEventAwareTrait;

    use SeanceFormAwareTrait;

    public function ajouterJourneeAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $seance = new Seance();
        $seance->setInstance($session);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/ajouter-journee', ['session' => $session->getId()], [], true));
        $form->bind($seance);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSeanceService()->create($seance);
                if ($seance->isPremiereSeance()) $this->getInscriptionClotureEvent()->updateEvent($seance->getInstance());
                if ($seance->isDerniereSeance()) $this->getDemandeRetourEvent()->updateEvent($seance->getInstance());
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/seance/modifier');
        $vm->setVariables([
            'title' => "Ajout d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierJourneeAction(): ViewModel
    {
        $seance = $this->getSeanceService()->getRequestedSeance($this);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/modifier-journee', ['journee' => $seance->getId()], [], true));
        $form->bind($seance);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSeanceService()->update($seance);
                if ($seance->isPremiereSeance()) $this->getInscriptionClotureEvent()->updateEvent($seance->getInstance());
                if ($seance->isDerniereSeance()) $this->getDemandeRetourEvent()->updateEvent($seance->getInstance());
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/seance/modifier');
        $vm->setVariables([
            'title' => "Modification d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserJourneeAction(): Response
    {
        $seance = $this->getSeanceService()->getRequestedSeance($this);
        if ($seance->isPremiereSeance()) $this->getInscriptionClotureEvent()->updateEvent($seance->getInstance());
        if ($seance->isDerniereSeance()) $this->getDemandeRetourEvent()->updateEvent($seance->getInstance());
        $this->getSeanceService()->historise($seance);

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $seance->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction(): Response
    {
        $seance = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->restore($seance);
        if ($seance->isPremiereSeance()) $this->getInscriptionClotureEvent()->updateEvent($seance->getInstance());
        if ($seance->isDerniereSeance()) $this->getDemandeRetourEvent()->updateEvent($seance->getInstance());

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $seance->getInstance()->getId()], [], true);
    }

    public function supprimerJourneeAction()
    {
        $seance = $this->getSeanceService()->getRequestedSeance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                if ($seance->isPremiereSeance()) $this->getInscriptionClotureEvent()->updateEvent($seance->getInstance());
                if ($seance->isDerniereSeance()) $this->getDemandeRetourEvent()->updateEvent($seance->getInstance());
                $this->getSeanceService()->delete($seance);

            }
            exit();
        }

        $vm = new ViewModel();
        if ($seance !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la journée de formation du [" . $seance->getDateDebut()->format('d/m/Y') . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/session/supprimer-journee', ["journee" => $seance->getId()], [], true),
            ]);
        }
        return $vm;
    }
}