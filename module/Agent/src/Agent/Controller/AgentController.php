<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Agent\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Application\Assertion\ChaineAssertion;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Provider\Parametre\AgentParametres;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
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

    public function afficherStatutsGradesAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent, false);
        $agentAffectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent, false);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent, false);

        $param = $this->getParametreService()->getParametreByCode('GLOBAL', 'CODE_UNIV');
        $codeEtabPrincipal = ($param) ? $param->getValeur() : null;

        return new ViewModel([
            'title' => 'Listing de tous les statuts et grades de ' . $agent->getDenomination(),
            'agent' => $agent,
            'affectations' => $agentAffectations,
            'statuts' => $agentStatuts,
            'grades' => $agentGrades,
            'codeEtabPrincipal' => $codeEtabPrincipal,
        ]);
    }

    /** Recherche d'agent  ********************************************************************************************/

    public function rechercherLargeAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsLargeByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);

        }
        exit;
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherWithStructureMereAction(): JsonModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        if ($structure === null) {
            throw new RuntimeException("Aucune structure de fournie");
        }

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term, $structures);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }
}