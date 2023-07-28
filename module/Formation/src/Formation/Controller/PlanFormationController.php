<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PlanFormationController extends AbstractActionController
{
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;

    use FormationInstanceServiceAwareTrait;

    public function afficherAction(): ViewModel
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
}