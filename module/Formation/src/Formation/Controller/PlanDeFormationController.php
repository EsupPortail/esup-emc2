<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\PlanDeFormation;
use Formation\Form\PlanDeFormation\PlanDeFormationFormAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationFormAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PlanDeFormationController extends AbstractActionController
{
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;

    use PlanDeFormationFormAwareTrait;
    use SelectionFormationFormAwareTrait;
    use SelectionPlanDeFormationFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $plans = $this->getPlanDeFormationService()->getPlansDeFormation();
        return new ViewModel([
            'plans' => $plans,
        ]);
    }

    public function courantAction(): ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getPlanDeFormationByAnnee();

        if (empty($planDeFormation)) {
            return new ViewModel();
        }

        $formations = $planDeFormation->getFormations();
        $sansgroupe = new FormationGroupe();
        $sansgroupe->setLibelle("Formations sans groupe");

        $groupes = [];
        $formationsArrayByGroupe = [];
        foreach ($formations as $formation) {
            if ($formation->getGroupe()) {
                $groupes[$formation->getGroupe()->getId()] = $formation->getGroupe();
                $formationsArrayByGroupe[$formation->getGroupe()->getId()][] = $formation;
            } else {
                $groupes[-1] = $sansgroupe;
                $formationsArrayByGroupe[-1][] = $formation;
            }
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

    public function afficherAction(): ViewModel
    {
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $formations = $plan->getFormations();

        $sansgroupe = new FormationGroupe();
        $sansgroupe->setLibelle("Formations sans groupe");

        $groupes = [];
        $formationsArrayByGroupe = [];
        foreach ($formations as $formation) {
            if ($formation->getGroupe()) {
                $groupes[$formation->getGroupe()->getId()] = $formation->getGroupe();
                $formationsArrayByGroupe[$formation->getGroupe()->getId()][] = $formation;
            } else {
                $groupes[-1] = $sansgroupe;
                $formationsArrayByGroupe[-1][] = $formation;
            }
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

    public function ajouterAction(): ViewModel
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

    public function modifierAction(): ViewModel
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

    public function supprimerAction(): ViewModel
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
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du plan de formation " . $plan->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('plan-de-formation/supprimer', ["plan-de-formation" => $plan->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des formations d'un plan de formation **************************************************************/

    public function gererFormationsAction() : ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/gerer-formations', ['plan-de-formation' => $planDeFormation->getId()], [], true));
        $form->bind($planDeFormation);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formationIds = $data['formations'];

            foreach ($planDeFormation->getFormations() as $formation) {
                if (!in_array($formation->getId(), $formationIds)) {
                    $formation->removePlanDeFormation($planDeFormation);
                    $this->getFormationService()->update($formation);
                }
            }
            foreach ($formationIds as $formationId) {
                $formation = $this->getFormationService()->getFormation($formationId);
                if (!$formation->hasPlanDeFormation($planDeFormation)) {
                    $formation->addPlanDeForamtion($planDeFormation);
                    $this->getFormationService()->update($formation);
                }
            }

            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajouter une formation au plan de formation [".$planDeFormation->getAnnee()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    /** Reprend les formations d'un plan de formation et les ajoute à plan courant */
    public function reprendreAction() : ViewModel
    {
        $planDeFormation = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $form = $this->getSelectionPlanDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('plan-de-formation/reprendre', ['plan-de-formation' => $planDeFormation->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $toRecopy = $this->getPlanDeFormationService()->getPlanDeFormation($data['plan']);
            if ($toRecopy !== null) {
                $this->getPlanDeFormationService()->transferer($toRecopy, $planDeFormation);
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Reprendre un plan de formation pour le plan de formation [".$planDeFormation->getAnnee()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }
}