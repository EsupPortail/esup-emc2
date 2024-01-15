<?php

namespace Observation\Controller;

use Laminas\Http\Response;
use Observation\Entity\Db\ObservationType;
use Observation\Form\ObservationType\ObservationTypeFormAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Observation\Service\ObservationType\ObservationTypeServiceAwareTrait;

class ObservationTypeController extends AbstractActionController
{
    use ObservationTypeServiceAwareTrait;
    use ObservationTypeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        //filtre ?

        $types = $this->getObservationTypeService()->getObservationsTypes('libelle', 'ASC', true);
        $categories = []; foreach ($types as $type) { $categories[$type->getCategorie()] = $type->getCategorie(); }
        $params = $this->params()->fromQuery();

        $types = $this->getObservationTypeService()->getObservationsTypesWithFiltre($params);

        return new ViewModel([
            'types' => $types,
            'categories' => $categories,
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $type = $this->getObservationTypeService()->getRequestedObservationType($this);

        return new ViewModel([
            'title' => "Affiche du type de validation",
            'type' => $type,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $type = new ObservationType();
        $form = $this->getObservationTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('observation/otype/ajouter', [], [], true));
        $form->bind($type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationTypeService()->create($type);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un type d'observation",
            'form' => $form,
        ]);
        $vm->setTemplate('observation/default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $type = $this->getObservationTypeService()->getRequestedObservationType($this);
        $form = $this->getObservationTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('observation/otype/modifier', ['observation-type' => $type->getId()], [], true));
        $form->bind($type);
        $form->setOldCode($type->getCode());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationTypeService()->update($type);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un type d'observation",
            'form' => $form,
        ]);
        $vm->setTemplate('observation/default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $type = $this->getObservationTypeService()->getRequestedObservationType($this);
        $this->getObservationTypeService()->historise($type);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('observation/otype');
    }

    public function restaurerAction(): Response
    {
        $type = $this->getObservationTypeService()->getRequestedObservationType($this);
        $this->getObservationTypeService()->restore($type);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('observation/otype');
    }

    public function supprimerAction(): ViewModel
    {
        $type = $this->getObservationTypeService()->getRequestedObservationType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getObservationTypeService()->delete($type);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('observation/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type d'observationt " . $type->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('observation/otype/supprimer', ["observation-type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }
}