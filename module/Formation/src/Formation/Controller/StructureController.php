<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Formation\Entity\Db\Formation;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class StructureController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction() : ViewModel
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

    public function afficherAction() : ViewModel
    {
        /**  Récupération du sous-arbre des structures */
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $role = $this->getUserService()->getConnectedRole();
        $utilisateur = $this->getUserService()->getConnectedUser();
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole($utilisateur, $role);
        if (!empty($selecteur) AND $structure===null) $structure = $selecteur[0];

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] =  $structure;

        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = $this->getStructureService()->getAgentsForces($structure);
        $agentsForces = array_map(function (StructureAgentForce $a) { return $a->getAgent(); }, $agentsForces);
        $allAgents = array_merge($agents, $agentsForces);

        //formations
        $demandesNonValidees =  $this->getDemandeExterneService()->getDemandesExternesNonValideesByAgents($allAgents, Formation::getAnnee());
        $demandesValidees =  $this->getDemandeExterneService()->getDemandesExternesValideesByAgents($allAgents, Formation::getAnnee());
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
}