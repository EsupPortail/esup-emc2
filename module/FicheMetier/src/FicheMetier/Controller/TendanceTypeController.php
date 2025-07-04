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
        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes('ordre','ASC', true);

        return new ViewModel([
            'tendancesTypes' => $tendancesTypes,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        return new ViewModel([
            'title' => "Affichage du type de thématique",
            'tendanceType' => $tendanceType,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $tendanceType = new TendanceType();

        $form = $this->getTendanceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/tendance-type/ajouter', [], [], true));
        $form->bind($tendanceType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTendanceTypeService()->create($tendanceType);
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
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        $form = $this->getTendanceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/tendance-type/modifier', ['tendance-type' => $tendanceType->getId()], [], true));
        $form->setOldCode($tendanceType->getCode());
        $form->bind($tendanceType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTendanceTypeService()->update($tendanceType);
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
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);
        $this->getTendanceTypeService()->historise($tendanceType);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/tendance-type', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);
        $this->getTendanceTypeService()->restore($tendanceType);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/tendance-type', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getTendanceTypeService()->delete($tendanceType);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($tendanceType !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type [".$tendanceType->getLibelle()."]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/tendance-type/supprimer', ["tendance-type" => $tendanceType->getId()], [], true),
            ]);
        }
        return $vm;
    }
}