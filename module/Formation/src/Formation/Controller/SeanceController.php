<?php

namespace Formation\Controller;

use Formation\Entity\Db\Seance;
use Formation\Event\Convocation\ConvocationEventAwareTrait;
use Formation\Event\DemandeRetour\DemandeRetourEventAwareTrait;
use Formation\Event\InscriptionCloture\InscriptionClotureEventAwareTrait;
use Formation\Event\RappelAgent\RappelAgentEventAwareTrait;
use Formation\Event\SessionCloture\SessionClotureEventAwareTrait;
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

    use ConvocationEventAwareTrait;
    use DemandeRetourEventAwareTrait;
    use InscriptionClotureEventAwareTrait;
    use RappelAgentEventAwareTrait;
    use SessionClotureEventAwareTrait;

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
                $this->gererEvenement($seance);
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
                $this->gererEvenement($seance);
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
        $this->gererEvenement($seance);
        $this->getSeanceService()->historise($seance);

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $seance->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction(): Response
    {
        $seance = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->restore($seance);
        $this->gererEvenement($seance);

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
                $this->gererEvenement($seance);
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


    public function gererEvenement(Seance $seance): void
    {
        if ($seance->isPremiereSeance()) {
            $this->getInscriptionClotureEvent()->updateEvent($seance->getInstance());
            $this->getConvocationEvent()->updateEvent($seance->getInstance());
            $this->getRappelAgentEvent()->updateEvent($seance->getInstance());
        }
        if ($seance->isDerniereSeance()) {
            $this->getDemandeRetourEvent()->updateEvent($seance->getInstance());
            $this->getSessionClotureEvent()->updateEvent($seance->getInstance());
        }
    }
}