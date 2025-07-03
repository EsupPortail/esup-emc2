<?php

namespace FicheMetier\Controller;

use FicheMetier\Entity\Db\TendanceType;
use FicheMetier\Form\TendanceType\TendanceTypeFormAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TendanceTypeController extends AbstractActionController {
    use TendanceTypeServiceAwareTrait;
    use TendanceTypeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $thematiquesTypes = $this->getTendanceTypeService()->getTendancesTypes('ordre','ASC', true);

        return new ViewModel([
            'thematiquesTypes' => $thematiquesTypes,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $thematiqueType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        return new ViewModel([
            'title' => "Affichage du type de thématique",
            'thematiqueType' => $thematiqueType,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $thematiqueType = new TendanceType();

        $form = $this->getTendanceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/thematique-type/ajouter', [], [], true));
        $form->bind($thematiqueType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTendanceTypeService()->create($thematiqueType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un type de thématique",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }


    public function modifierAction(): ViewModel
    {
        $thematiqueType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        $form = $this->getTendanceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/thematique-type/modifier', ['thematique-type' => $thematiqueType->getId()], [], true));
        $form->setOldCode($thematiqueType->getCode());
        $form->bind($thematiqueType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTendanceTypeService()->update($thematiqueType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modificaiton d'un type de thématique",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $thematiqueType = $this->getTendanceTypeService()->getRequestedTendanceType($this);
        $this->getTendanceTypeService()->historise($thematiqueType);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/thematique-type', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $thematiqueType = $this->getTendanceTypeService()->getRequestedTendanceType($this);
        $this->getTendanceTypeService()->restore($thematiqueType);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/thematique-type', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $thematiqueType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getTendanceTypeService()->delete($thematiqueType);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($thematiqueType !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type [".$thematiqueType->getLibelle()."]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/thematique-type/supprimer', ["thematique-type" => $thematiqueType->getId()], [], true),
            ]);
        }
        return $vm;
    }
}