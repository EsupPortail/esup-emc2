<?php

namespace Application\Controller;

use Application\Entity\Db\CompetenceMaitrise;
use Application\Form\CompetenceMaitrise\CompetenceMaitriseFormAwareTrait;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompetenceMaitriseController extends AbstractActionController
{
    use CompetenceMaitriseServiceAwareTrait;
    use CompetenceMaitriseFormAwareTrait;

    public function afficherAction()
    {
        $maitrise = $this->getCompetenceMaitriseService()->getRequestedCompetenceMaitrise($this);

        $vm = new ViewModel([
            'title' => "Affichage d'un niveau de maîtrise",
            'maitrise' => $maitrise,
        ]);
        $vm->setTemplate('application/competence/afficher-competence-maitrise');
        return $vm;
    }

    public function ajouterAction()
    {
        $maitrise = new CompetenceMaitrise();
        $form = $this->getCompetenceMaitriseForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-maitrise/ajouter', [], [], true));
        $form->bind($maitrise);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceMaitriseService()->create($maitrise);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un niveau de maîtrise",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function modifierAction()
    {
        $maitrise = $this->getCompetenceMaitriseService()->getRequestedCompetenceMaitrise($this);
        $form = $this->getCompetenceMaitriseForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-maitrise/modifier', ['maitrise' => $maitrise->getId()], [], true));
        $form->bind($maitrise);
        $form->get('old-niveau')->setValue($maitrise->getNiveau());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $old = $form->get('old-niveau')->getValue();
                $value = $form->get('niveau')->getValue();
                $this->getCompetenceMaitriseService()->update($maitrise);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un niveau de maîtrise",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function historiserAction()
    {
        $maitrise = $this->getCompetenceMaitriseService()->getRequestedCompetenceMaitrise($this);
        $this->getCompetenceMaitriseService()->historise($maitrise);
        return $this->redirect()->toRoute('competence', [], ['fragment' => 'niveau'], true);
    }

    public function restaurerAction()
    {
        $maitrise = $this->getCompetenceMaitriseService()->getRequestedCompetenceMaitrise($this);
        $this->getCompetenceMaitriseService()->restore($maitrise);
        return $this->redirect()->toRoute('competence', [], ['fragment' => 'niveau'], true);
    }

    public function supprimerAction()
    {
        $maitrise = $this->getCompetenceMaitriseService()->getRequestedCompetenceMaitrise($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceMaitriseService()->delete($maitrise);
            exit();
        }

        $vm = new ViewModel();
        if ($maitrise !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du niveau de maîtrise " . $maitrise->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence-maitrise/supprimer', ["competence-maitrise" => $maitrise->getId()], [], true),
            ]);
        }
        return $vm;
    }
}