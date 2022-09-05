<?php

namespace Formation\Controller;

use Formation\Entity\Db\Seance;
use Formation\Form\Seance\SeanceFormAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SeanceController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use SeanceFormAwareTrait;

    public function ajouterJourneeAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $journee = new Seance();
        $journee->setInstance($instance);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-journee', ['formation-instance' => $instance->getId()], [], true));
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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierJourneeAction() : ViewModel
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-journee', ['journee' => $journee->getId()], [], true));
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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserJourneeAction() : Response
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->historise($journee);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $journee->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction() : Response
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->restore($journee);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $journee->getInstance()->getId()], [], true);
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
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la journée de formation du [" . $journee->getJour()->format('d/m/Y') . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-journee', ["journee" => $journee->getId()], [], true),
            ]);
        }
        return $vm;
    }
}