<?php

namespace Formation\Controller;

use Application\Entity\Db\AgentAffectation;
use Application\Entity\Db\AgentStatut;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Formation\Entity\Db\Formation;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;

class StructureController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $structures_ = $this->getStructureService()->getStructures();

        // classer selon le chemin
        $structures = [];
        foreach ($structures_ as $structure) {
            $chemin = $structure->computeChemin();
            $structures[$chemin] = $structure;
        }
        ksort($structures);

        return new ViewModel([
            'structures' => $structures
        ]);
    }

    public function afficherAction(): ViewModel
    {
        /**  Récupération du sous-arbre des structures */
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();
        if (!empty($selecteur) and $structure === null) $structure = $selecteur[0];

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = $this->getStructureService()->getAgentsForces($structure);
        $agentsForces = array_map(function (StructureAgentForce $a) {
            return $a->getAgent();
        }, $agentsForces);
        $allAgents = array_merge($agents, $agentsForces);

        //formations
        $demandesNonValidees = $this->getDemandeExterneService()->getDemandesExternesNonValideesByAgents($allAgents, Formation::getAnnee());
        $demandesValidees = $this->getDemandeExterneService()->getDemandesExternesValideesByAgents($allAgents, Formation::getAnnee());
        $inscriptionsNonValidees = $this->getFormationInstanceInscritService()->getInscriptionsNonValideesByAgents($allAgents, Formation::getAnnee());
        $inscriptionsValidees = $this->getFormationInstanceInscritService()->getInscriptionsValideesByAgents($allAgents, Formation::getAnnee());

        return new ViewModel([
            'selecteur' => $selecteur,

            'structure' => $structure,
            'agents' => $allAgents,

            'responsables' => $this->getStructureService()->getResponsables($structure, new DateTime()),
            'gestionnaires' => $this->getStructureService()->getGestionnaires($structure, new DateTime()),

            'demandesNonValidees' => $demandesNonValidees,
            'demandesValidees' => $demandesValidees,
            'inscriptionsNonValidees' => $inscriptionsNonValidees,
            'inscriptionsValidees' => $inscriptionsValidees,
        ]);
    }

    public function listerLesAgentsAction(): ViewModel
    {
        /**  Récupération du sous-arbre des structures */
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = $this->getStructureService()->getAgentsForces($structure);
        $agentsForces = array_map(function (StructureAgentForce $a) {
            return $a->getAgent();
        }, $agentsForces);
        $allAgents = array_merge($agents, $agentsForces);

        return new ViewModel([
            'title' => "Liste des agents liés à la structure",
            'agents' => $allAgents,
        ]);
    }

    public function extractionFormationsAction(): JsonModel
    {
        /**  Récupération du sous-arbre des structures */
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = $this->getStructureService()->getAgentsForces($structure);
        $agentsForces = array_map(function (StructureAgentForce $a) {
            return $a->getAgent();
        }, $agentsForces);
        $allAgents = array_merge($agents, $agentsForces);

        $header = ['Dénomination', 'Statuts', 'Affectation', 'Formation', 'Période', 'Volume suivi', 'Volume dispensé'];
        $formations = $this->getFormationInstanceInscritService()->getFormationsByAgents($allAgents);

        $result = [];
        foreach ($formations as $formation) {
            $agent = $formation->getAgent();
            $session = $formation->getInstance();
            $action = $session->getFormation();
            $result[] = [
                'Dénomination' => $agent->getDenomination(),
                'Status' => implode("\n", AgentStatut::generateStatutsArray($agent->getStatutsActifs())),
                'Affectations' => implode("\n", AgentAffectation::generateAffectationsArray($agent->getAffectationsActifs())),
                'Libellé' => $action->getLibelle(),
                'Période' => $session->getPeriode(),
                'Volume suivi' => $formation->getDureePresence(),
                'Volume dispensé' => $session->getDuree(),
            ];
        }

        $filename = (new DateTime())->format("Ymd-his") . "_extraction_formations.csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($header);
        $CSV->setData($result);
        $CSV->setFilename($filename);
        return $CSV;
    }
}