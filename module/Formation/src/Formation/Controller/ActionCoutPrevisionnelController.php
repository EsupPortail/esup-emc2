<?php

namespace Formation\Controller;

use Formation\Entity\Db\ActionCoutPrevisionnel;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelFormAwareTrait;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ActionCoutPrevisionnelController extends AbstractActionController
{
    use ActionCoutPrevisionnelServiceAwareTrait;
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;
    use ActionCoutPrevisionnelFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        if (!isset($params['histo'])) $params['histo'] = "non";

        $actionsCoutsPrevisionnels = $this->getActionCoutPrevisionnelService()->getActionsCoutsPrevisionnelsWithFiltre($params);
        $plans = $this->getPlanDeFormationService()->getPlansDeFormation();
        $actions = $this->getFormationService()->getFormations();

        return new ViewModel([
            'actionsCoutsPrevisionnels' => $actionsCoutsPrevisionnels,
            'plans' => $plans,
            'actions' => $actions,
            'params' => $params,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $action = $this->getFormationService()->getRequestedFormation($this, 'action-de-formation');
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $actionCoutPrevisionnel = new ActionCoutPrevisionnel();
        $actionCoutPrevisionnel->setAction($action);
        $actionCoutPrevisionnel->setPlan($plan);

        $form = $this->getActionCoutPrevisionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/action-cout-previsionnel/ajouter', ['action' => $action?->getId(), 'plan-de-formation' => $plan?->getId()], [], true));
        $form->bind($actionCoutPrevisionnel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActionCoutPrevisionnelService()->create($actionCoutPrevisionnel);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un coût provisionnel associé à une action",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $actionCoutPrevisionnel = $this->getActionCoutPrevisionnelService()->getRequestedActionCoutPrevisionnel($this);

        $form = $this->getActionCoutPrevisionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/action-cout-previsionnel/modifier', ['action-cout-previsionnel' => $actionCoutPrevisionnel->getId()], [], true));
        $form->bind($actionCoutPrevisionnel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActionCoutPrevisionnelService()->update($actionCoutPrevisionnel);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un coût provisionnel associé à une action",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $actionCoutPrevisionnel = $this->getActionCoutPrevisionnelService()->getRequestedActionCoutPrevisionnel($this);
        $this->getActionCoutPrevisionnelService()->historise($actionCoutPrevisionnel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/action-cout-previsionnel', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $actionCoutPrevisionnel = $this->getActionCoutPrevisionnelService()->getRequestedActionCoutPrevisionnel($this);
        $this->getActionCoutPrevisionnelService()->restore($actionCoutPrevisionnel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/action-cout-previsionnel', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $actionCoutPrevisionnel = $this->getActionCoutPrevisionnelService()->getRequestedActionCoutPrevisionnel($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getActionCoutPrevisionnelService()->delete($actionCoutPrevisionnel);
            exit();
        }

        $vm = new ViewModel();
        if ($actionCoutPrevisionnel !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du coût provisionnel associé à une action de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/action-cout-previsionnel/supprimer', ["action-cout-previsionnel" => $actionCoutPrevisionnel->getId()], [], true),
            ]);
        }
        return $vm;

    }
}