<?php

namespace Observation\Controller;

use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Observation\Entity\Db\ObservationInstance;
use Observation\Form\ObservationInstance\ObservationInstanceFormAwareTrait;
use Observation\Provider\Validation\ObservationValidations;
use Observation\Service\ObservationInstance\ObservationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

class ObservationInstanceController extends AbstractActionController
{
    use ObservationInstanceServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ObservationInstanceFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $observations = $this->getObservationInstanceService()->getObservationsInstances('histoCreation', 'DESC', true);
        $params = $this->params()->fromQuery();

        return new ViewModel([
            'observations' => $observations,
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $observation = $this->getObservationInstanceService()->getRequestedObservationInstance($this);

        return new ViewModel([
            'observation' => $observation,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $observation = new ObservationInstance();
        $form = $this->getObservationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('observation/instance/ajouter', [], [], true));
        $form->bind($observation);

        $form->cacherType();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data  = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationInstanceService()->create($observation);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une observation",
            'form' => $form,
        ]);
        $vm->setTemplate('observation/default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $observation = $this->getObservationInstanceService()->getRequestedObservationInstance($this);
        $form = $this->getObservationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('observation/instance/modifier', ['observation' => $observation->getId()], [], true));
        $form->bind($observation);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data  = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationInstanceService()->update($observation);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'une observation",
            'form' => $form,
        ]);
        $vm->setTemplate('observation/default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $observation = $this->getObservationInstanceService()->getRequestedObservationInstance($this);
        $this->getObservationInstanceService()->historise($observation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('observation/instance');
    }

    public function restaurerAction(): Response
    {
        $observation = $this->getObservationInstanceService()->getRequestedObservationInstance($this);
        $this->getObservationInstanceService()->restore($observation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('observation/instance');
    }

    public function supprimerAction(): ViewModel
    {
        $observation = $this->getObservationInstanceService()->getRequestedObservationInstance($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getObservationInstanceService()->delete($observation);
            exit();
        }

        $vm = new ViewModel();
        if ($observation !== null) {
            $vm->setTemplate('observation/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type d'observation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('observation/instance/supprimer', ["observation" => $observation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function validerAction(): ViewModel
    {
        $observation = $this->getObservationInstanceService()->getRequestedObservationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = $observation->getValidationActiveByTypeCode(ObservationValidations::OBSERVATION_VALIDEE);
            if ($validation === null) {
                if ($data["reponse"] === "oui") {
                    $this->getValidationInstanceService()->setValidationActive($observation, ObservationValidations::OBSERVATION_VALIDEE);
                    $this->getObservationInstanceService()->update($observation);
                }
//                if ($data["reponse"] === "non") {
//                    $this->getValidationInstanceService()->setValidationActive($ficheposte, $type, 'Refus');
//                    $this->getFichePosteService()->update($ficheposte);
//                }
            }
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
        $vm->setVariables([
            'title' => "Validation de l'obsevation",
            'text' => "La validation de l'observation figera celle-ci. Êtes-vous de vouloir valider cette validation ?",
            'action' => $this->url()->fromRoute('observation/instance/valider', ['observation-instance' => $observation->getId()], [], true),
            'refus' => false,
        ]);
        return $vm;
    }


}