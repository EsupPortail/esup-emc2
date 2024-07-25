<?php

namespace Formation\Controller;

use Formation\Entity\Db\Seance;
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
    use SeanceFormAwareTrait;

    public function ajouterJourneeAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $journee = new Seance();
        $journee->setInstance($session);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/ajouter-journee', ['session' => $session->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSeanceService()->create($journee);

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
        $journee = $this->getSeanceService()->getRequestedSeance($this);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/modifier-journee', ['journee' => $journee->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSeanceService()->update($journee);
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
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->historise($journee);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $journee->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction(): Response
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->restore($journee);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $journee->getInstance()->getId()], [], true);
    }

    public function supprimerJourneeAction()
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getSeanceService()->delete($journee);
            exit();
        }

        $vm = new ViewModel();
        if ($journee !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la journée de formation du [" . $journee->getDateDebut()->format('d/m/Y') . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/session/supprimer-journee', ["journee" => $journee->getId()], [], true),
            ]);
        }
        return $vm;
    }
}