<?php

namespace Formation\Controller;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Formation;
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
        $superieures = $this->getAgentService()->computeSuperieures($agent);
        $autorites = $this->getAgentService()->computeAutorites($agent);

        $formations = $this->getFormationInstanceInscritService()->getFormationsBySuivies($agent);
        $inscriptions = $this->getFormationInstanceInscritService()->getFormationsByInscrit($agent);

        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgent($agent);
        $demandes = array_filter($demandes, function (DemandeExterne $d) { return $d->estNonHistorise() AND $d->getEtat()->getCode() !== DemandeExterneEtats::ETAT_REJETEE AND $d->getEtat()->getCode() !== DemandeExterneEtats::ETAT_TERMINEE;});
//        $demandesValidees    = array_filter($demandes, function (DemandeExterne $d) { return $d->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS; });

        return new ViewModel([
            'agent' => $agent,
            'agentAffectations' => $agentAffectations,
            'agentGrades' => $agentGrades,
            'agentStatuts' => $agentStatuts,

            'superieures' => $superieures,
            'autorites' => $autorites,

            'inscriptions' => $inscriptions,
            'stages' => $demandes,
            'formations' => $formations,
        ]);
    }

    public function mesAgentsAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $agents = $this->getAgentService()->getAgentsByResponsabilite($user, $role);

        $inscriptionsValidees = $this->getFormationInstanceInscritService()->getInscriptionsValideesByAgents($agents, null);
        $inscriptionsNonValidees = $this->getFormationInstanceInscritService()->getInscriptionsNonValideesByAgents($agents, null);
        $demandesValidees =  $this->getDemandeExterneService()->getDemandesExternesValideesByAgents($agents, Formation::getAnnee());
        $demandesNonValidees =  $this->getDemandeExterneService()->getDemandesExternesNonValideesByAgents($agents, Formation::getAnnee());


        return new ViewModel([
            'user' => $user,
            'role' => $role,
            'agents' => $agents,

            'inscriptionsValidees' => $inscriptionsValidees,
            'inscriptionsNonValidees' => $inscriptionsNonValidees,
            'demandesValidees' => $demandesValidees,
            'demandesNonValidees' => $demandesNonValidees,
        ]);
    }

    public function listerMesAgentsAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $agents = $this->getAgentService()->getAgentsByResponsabilite($user, $role);
        usort($agents, function (Agent $a, Agent $b) {
            $aaa = $a->getNomUsuel()." ".$a->getPrenom()." ".$a->getId();
            $bbb = $b->getNomUsuel()." ".$b->getPrenom()." ".$b->getId();
            return $aaa > $bbb;
        });
        return new ViewModel([
            'title' => "Liste des agents dont je suis responsable",
            'user' => $user,
            'role' => $role,
            'agents' => $agents,
        ]);
    }
}