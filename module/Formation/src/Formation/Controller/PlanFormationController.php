<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationGroupe;
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
        $groupes = array_filter($groupes, function (FormationGroupe $a) {
            $formationsListes = $a->getFormations();
            $formationsListes = array_filter($formationsListes, function (Formation $a) {
                return $a->getAffichage() and $a->estNonHistorise();
            });
            return !empty($formationsListes);
        });
        $formations = [];
        $sessions = [];
        foreach ($groupes as $groupe) {
            $formationsListes = $this->getFormationService()->getFormationsByGroupe($groupe);
            $formationsListes = array_filter($formationsListes, function (Formation $a) { return $a->getAffichage();});

            $formations[$groupe->getId()] = $formationsListes;
            foreach ($formations[$groupe->getId()] as $formation) {
                $sessions[$formation->getId()] = $this->getFormationInstanceService()->getFormationsInstancesByFormation($formation);
            }
        }

        $abonnements = [];
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent !== null) $abonnements = $this->getAbonnementService()->getAbonnementsByAgent($agent);


        $annee = Formation::getAnnee();

        return new ViewModel([
            'groupes' => $groupes,
            'formations' => $formations,
            'sessions' => $sessions,
            'annee' => $annee ."/" . ($annee+1),
            'abonnements' => $abonnements,
        ]);
    }
}