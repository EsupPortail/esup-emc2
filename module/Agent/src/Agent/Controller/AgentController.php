<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Agent\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Application\Assertion\ChaineAssertion;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Provider\Parametre\AgentParametres;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentController extends AbstractActionController {


    public ChaineAssertion $chaineAssertion;

    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;


    public function acquisAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $applications = $agent->getApplicationDictionnaireComplet();
        $competences = $agent->getCompetenceDictionnaireComplet();

        return new ViewModel([
            'agent' => $agent,
            'applications' => $applications,
            'competences' => $competences,
        ]);
    }

    public function informationsAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($agent === null) $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent n'a pu être trouvé.");

        //Récupération des status
        $agentAffectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgentAndDate($agent);
        $agentEchelons = $agent->getEchelonsActifs();
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent);
        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent);

        //Récupération des supérieures et autorités
        $superieurs = array_map(
            function (AgentSuperieur $a) {
                return $a->getSuperieur();
            },
            $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent));
        $autorites = array_map(
            function (AgentAutorite $a) {
                return $a->getAutorite();
            },
            $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent));


        return new ViewModel([
            'agent' => $agent,
            'autorites' => $autorites,
            'superieurs' => $superieurs,
            'agentAffectations' => $agentAffectations,
            'agentEchelons' => $agentEchelons,
            'agentGrades' => $agentGrades,
            'agentStatuts' => $agentStatuts,

            'chaineAssertion' => $this->chaineAssertion,

            // aide pour la campagne
            'connectedUser' => $this->getUserService()->getConnectedUser(),
            'connectedRole' => $this->getUserService()->getConnectedRole(),
            'campagnesActives' => $this->getCampagneService()->getCampagnesActives(),

        ]);
    }

    public function missionsSpecifiquesAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $missions = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgent($agent, false);

        return new ViewModel([
           'agent' => $agent,
           'missions' => $missions,
        ]);
    }

    public function portfolioAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $fichiers = $agent->getFichiers();

        return new ViewModel([
            'agent' => $agent,
            'fichiers' => $fichiers,
            // onglet
            'parametres' => $this->getParametreService()->getParametresByCategorieCode(AgentParametres::TYPE),
        ]);
    }
}