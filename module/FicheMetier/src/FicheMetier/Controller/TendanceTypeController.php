<?php

namespace FicheMetier\Controller;

use FicheMetier\Entity\Db\TendanceType;
use FicheMetier\Form\TendanceType\TendanceTypeFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class TendanceTypeController extends AbstractActionController {
    use TendanceTypeServiceAwareTrait;
    use TendanceTypeFormAwareTrait;
    use ParametreServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes('ordre','ASC', true);
        $parametres = $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE);

        return new ViewModel([
            'tendancesTypes' => $tendancesTypes,
            'parametres' => $parametres,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        return new ViewModel([
            'title' => "Affichage du type de \"Tendance d'évolution\"",
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
            'title' => "Ajout d'un type de \"Tendance d'évolution\"",
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
            'title' => "Modificaiton d'un type de \"Tendance d'évolution\"",
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