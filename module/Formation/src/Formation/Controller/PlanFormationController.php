<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PlanFormationController extends AbstractActionController {
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;

    public function afficherAction() : ViewModel
    {
        $groupes = $this->getFormationGroupeService()->getFormationsGroupes('libelle');

        $formations = [];
        $sessions = [];
        foreach ($groupes as $groupe) {
            $formations[$groupe->getId()] = $this->getFormationService()->getFormationsByGroupe($groupe);
            foreach ($formations[$groupe->getId()] as $formation) {
                $sessions[$formation->getId()] = $this->getFormationInstanceService()->getFormationsInstancesByFormation($formation);
            }
        }

        $abonnements = [];
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent !== null) $abonnements = $this->getAbonnementService()->getAbonnementsByAgent($agent);

        return new ViewModel([
            'groupes' => $groupes,
            'formations' => $formations,
            'sessions' => $sessions,
            'annee' => '2021/2022',
            'abonnements' => $abonnements,
        ]);
    }
}