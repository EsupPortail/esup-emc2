<?php

namespace Formation\Controller;

use Formation\Entity\Db\Axe;
use Formation\Form\Axe\AxeFormAwareTrait;
use Formation\Service\Axe\AxeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AxeController extends AbstractActionController {
    use AxeServiceAwareTrait;
    use AxeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $axes = $this->getAxeService()->getAxes('ordre', 'ASC', true);

        return new ViewModel([
            'axes' => $axes,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $axe = $this->getAxeService()->getRequestedAxe($this);

        return new ViewModel([
            'title' => "Affichage de l'axe [".$axe->getLibelle()."]",
            'axe' => $axe,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $axe = new Axe();

        $form = $this->getAxeForm();
        $form->setAttribute('action', $this->url()->fromRoute('axe/ajouter', [], [], true));
        $form->bind($axe);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAxeService()->create($axe);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un axe de formation",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $axe = $this->getAxeService()->getRequestedAxe($this);

        $form = $this->getAxeForm();
        $form->setAttribute('action', $this->url()->fromRoute('axe/modifier', ['axe' => $axe->getId()], [], true));
        $form->bind($axe);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAxeService()->update($axe);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de l'axe [".$axe->getLibelle()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $axe = $this->getAxeService()->getRequestedAxe($this);
        $this->getAxeService()->historise($axe);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('axe', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $axe = $this->getAxeService()->getRequestedAxe($this);
        $this->getAxeService()->restore($axe);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('axe', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $axe = $this->getAxeService()->getRequestedAxe($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAxeService()->delete($axe);
            exit();
        }

        $vm = new ViewModel();
        if ($axe !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'axe " . $axe->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('axe/supprimer', ["axe" => $axe->getId()], [], true),
            ]);
        }
        return $vm;
    }
}