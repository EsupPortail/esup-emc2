<?php

namespace Element\Controller;

use Element\Entity\Db\CompetenceType;
use Element\Form\CompetenceType\CompetenceTypeFormAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CompetenceTypeController extends AbstractActionController {
    use CompetenceTypeServiceAwareTrait;
    use CompetenceTypeFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $types = $this->getCompetenceTypeService()->getCompetencesTypes(true);

        return new ViewModel([
            'types' => $types,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        return new ViewModel([
            'title' => "Affiche d'un type de compétence",
            'type' => $type,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $type = new CompetenceType();
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-type/ajouter', [], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceTypeService()->create($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-type/modifier', ['competence-type' => $type->getId()], [], true));
        $form->bind($type);
        $form->oldcode = $type->getCode();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $this->getCompetenceTypeService()->historise($type);
        return $this->redirect()->toRoute('element/competence-type', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $this->getCompetenceTypeService()->restore($type);
        return $this->redirect()->toRoute('element/competence-type', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceTypeService()->delete($type);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de le type de compétence  " . $type->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/competence-type/detruire', ["competence-type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }
}