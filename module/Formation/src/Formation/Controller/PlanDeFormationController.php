<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\PlanDeFormation;
use Formation\Form\PlanDeFormation\PlanDeFormationFormAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PlanDeFormationController extends AbstractActionController {
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;

    use PlanDeFormationFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $plans = $this->getPlanDeFormationService()->getPlansDeFormation();
        return new ViewModel([
            'plans' => $plans,
        ]);
    }

    public function courantAction() : ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getPlanDeFormationByAnnee();


        $formations = $planDeFormation->getFormations();

        $groupes = [];
        $formationsArrayByGroupe = [];
        foreach ($formations as $formation) {
            $groupes[$formation->getGroupe()->getId()] = $formation->getGroupe();
            $formationsArrayByGroupe[$formation->getGroupe()->getId()][] = $formation;
        }

        $sessionsArrayByFormation = [];
        foreach ($formations as $formation) {
            //todo recupérer par lot
            $sessionsArrayByFormation[$formation->getId()] = $this->getFormationInstanceService()->getFormationsInstancesOuvertesByFormation($formation);
        }

        $abonnements = [];
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent !== null) $abonnements = $this->getAbonnementService()->getAbonnementsByAgent($agent);

        return new ViewModel([
            'planDeFormation' => $planDeFormation,
            'formations' => $formations,
            'groupes' => $groupes,
            'formationsArrayByGroupe' => $formationsArrayByGroupe,
            'sessionsArrayByFormation' => $sessionsArrayByFormation,
            'abonnements' => $abonnements,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $formations = $plan->getFormations();

        $groupes = [];
        $formationsArrayByGroupe = [];
        foreach ($formations as $formation) {
            $groupes[$formation->getGroupe()->getId()] = $formation->getGroupe();
            $formationsArrayByGroupe[$formation->getGroupe()->getId()][] = $formation;
        }

        $sessionsArrayByFormation = [];
        foreach ($formations as $formation) {
            //todo recupérer par lot
            $sessionsArrayByFormation[$formation->getId()] = $this->getFormationInstanceService()->getFormationsInstancesByFormationAndPlan($formation, $plan);
        }

        return new ViewModel([
            'plan' => $plan,
            'formations' => $formations,
            'groupes' => $groupes,
            'formationsArrayByGroupe' => $formationsArrayByGroupe,
            'sessionsArrayByFormation' => $sessionsArrayByFormation,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $plan = new PlanDeFormation();

        $form = $this->getPlanDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/ajouter', [], [], true));
        $form->bind($plan);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPlanDeFormationService()->create($plan);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter un plan de formation",
            'form' => $form,
            ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $form = $this->getPlanDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/modifier', ['plan-de-formation' => $plan->getId()], [], true));
        $form->bind($plan);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPlanDeFormationService()->update($plan);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier le plan de formation",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function supprimerAction() : ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getPlanDeFormationService()->delete($plan);
            exit();
        }

        $vm = new ViewModel();
        if ($plan !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du plan de formation " . $plan->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('plan-de-formation/supprimer', ["plan-de-formation" => $plan->getId()], [], true),
            ]);
        }
        return $vm;
    }
}