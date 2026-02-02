<?php

namespace Element\Controller;

use Element\Entity\Db\NiveauMaitrise;
use Element\Form\Niveau\NiveauFormAwareTrait;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class NiveauController extends AbstractActionController
{
    use NiveauMaitriseServiceAwareTrait;
    use NiveauFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $niveaux = $this->getNiveauMaitriseService()->getMaitrisesNiveaux("", 'id', 'ASC', true);
        return new ViewModel(['niveaux' => $niveaux]);
    }

    public function afficherAction() : ViewModel
    {
        $maitrise = $this->getNiveauMaitriseService()->getRequestedMaitriseNiveau($this);

        $vm = new ViewModel([
            'title' => "Affichage d'un niveau de maîtrise",
            'maitrise' => $maitrise,
        ]);
        return $vm;
    }

    public function ajouterAction() : ViewModel
    {
        $maitrise = new NiveauMaitrise();
        $form = $this->getNiveauForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/niveau/ajouter', [], [], true));
        $form->bind($maitrise);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauMaitriseService()->create($maitrise);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un niveau de maîtrise",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction()  : ViewModel
    {
        $maitrise = $this->getNiveauMaitriseService()->getRequestedMaitriseNiveau($this);
        $form = $this->getNiveauForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/niveau/modifier', ['maitrise' => $maitrise->getId()], [], true));
        $form->bind($maitrise);
        $form->get('old-niveau')->setValue($maitrise->getNiveau());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauMaitriseService()->update($maitrise);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un niveau de maîtrise",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $maitrise = $this->getNiveauMaitriseService()->getRequestedMaitriseNiveau($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getNiveauMaitriseService()->historise($maitrise);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('competence', [], ['fragment' => 'niveau'], true);
    }

    public function restaurerAction() : Response
    {
        $maitrise = $this->getNiveauMaitriseService()->getRequestedMaitriseNiveau($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getNiveauMaitriseService()->restore($maitrise);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('competence', [], ['fragment' => 'niveau'], true);
    }

    public function supprimerAction() : ViewModel
    {
        $maitrise = $this->getNiveauMaitriseService()->getRequestedMaitriseNiveau($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getNiveauMaitriseService()->delete($maitrise);
            exit();
        }

        $vm = new ViewModel();
        if ($maitrise !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du niveau de maîtrise " . $maitrise->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/niveau/supprimer', ["niveau" => $maitrise->getId()], [], true),
            ]);
        }
        return $vm;
    }
}