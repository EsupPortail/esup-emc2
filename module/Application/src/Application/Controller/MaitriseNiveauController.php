<?php

namespace Application\Controller;

use Application\Entity\Db\MaitriseNiveau;
use Application\Form\MaitriseNiveau\MaitriseNiveauFormAwareTrait;
use Application\Service\MaitriseNiveau\MaitriseNiveauServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MaitriseNiveauController extends AbstractActionController
{
    use MaitriseNiveauServiceAwareTrait;
    use MaitriseNiveauFormAwareTrait;

    public function afficherAction()
    {
        $maitrise = $this->getMaitriseNiveauService()->getRequestedMaitriseNiveau($this);

        $vm = new ViewModel([
            'title' => "Affichage d'un niveau de maîtrise",
            'maitrise' => $maitrise,
        ]);
        $vm->setTemplate('application/competence/afficher-competence-maitrise');
        return $vm;
    }

    public function ajouterAction()
    {
        $type = $this->params()->fromRoute('type');
        $maitrise = new MaitriseNiveau();
        $maitrise->setType($type);
        $form = $this->getMaitriseNiveauForm();
        $form->setType($type);
        $form->setAttribute('action', $this->url()->fromRoute('competence-maitrise/ajouter', ['type' => $type], [], true));
        $form->bind($maitrise);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMaitriseNiveauService()->create($maitrise);
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
        $maitrise = $this->getMaitriseNiveauService()->getRequestedMaitriseNiveau($this);
        $form = $this->getMaitriseNiveauForm();
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
                $this->getMaitriseNiveauService()->update($maitrise);
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
        $maitrise = $this->getMaitriseNiveauService()->getRequestedMaitriseNiveau($this);
        $this->getMaitriseNiveauService()->historise($maitrise);
        return $this->redirect()->toRoute('competence', [], ['fragment' => 'niveau'], true);
    }

    public function restaurerAction()
    {
        $maitrise = $this->getMaitriseNiveauService()->getRequestedMaitriseNiveau($this);
        $this->getMaitriseNiveauService()->restore($maitrise);
        return $this->redirect()->toRoute('competence', [], ['fragment' => 'niveau'], true);
    }

    public function supprimerAction()
    {
        $maitrise = $this->getMaitriseNiveauService()->getRequestedMaitriseNiveau($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMaitriseNiveauService()->delete($maitrise);
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