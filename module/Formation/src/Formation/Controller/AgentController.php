<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use UserServiceAwareTrait;


    public function indexAction() : ViewModel
    {
        $params = $this->params()->fromQuery();
        $agents = [];
        if ($params !== null AND !empty($params)) {
            $agents = $this->getAgentService()->getAgentsWithFiltre($params);
        }

        return new ViewModel([
            'agents' => $agents,
            'params' => $params,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $agentAffectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent);
        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent);

        $formations = $this->getFormationInstanceInscritService()->getFormationsBySuivies($agent);
        $inscriptions = $this->getFormationInstanceInscritService()->getFormationsByInscrit($agent);

        $stages = [];
        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgent($agent);
        $demandes = array_filter($demandes, function (DemandeExterne $d) { return $d->estNonHistorise() AND $d->getEtat()->getCode() !== DemandeExterneEtats::ETAT_REJETEE AND $d->getEtat()->getCode() !== DemandeExterneEtats::ETAT_TERMINEE;});
//        $demandesValidees    = array_filter($demandes, function (DemandeExterne $d) { return $d->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS; });

        return new ViewModel([
            'agent' => $agent,
            'agentAffectations' => $agentAffectations,
            'agentGrades' => $agentGrades,
            'agentStatuts' => $agentStatuts,

            'inscriptions' => $inscriptions,
            'stages' => $demandes,
            'formations' => $formations,
        ]);
    }

    public function mesAgentsAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $agents = []; //$this->getAgentService()->getAgentsByResponsabilite($user, $role);
        return new ViewModel([
            'user' => $user,
            'role' => $role,
            'agents' => $agents,
        ]);
    }
}