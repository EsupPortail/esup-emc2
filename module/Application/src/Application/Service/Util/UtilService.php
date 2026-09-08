<?php

namespace Application\Service\Util;

use Agent\Entity\Db\Agent;
use Agent\Provider\Role\RoleProvider as AgentRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Provider\Role\RoleProvider as ApplicationRoleProvider;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Role\RoleProvider as EntretienProfessionnelRoleProvider;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** Service générique pour porter des méthodes trans-module */
class UtilService
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    /** @return Agent[] */
    public function getAgentsSousReponsabilite(UserInterface $user): array
    {
        $role = $this->getUserService()->getConnectedRole();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $now = new DateTime();

        switch ($role->getRoleId()) {
            case ApplicationRoleProvider::ADMIN_TECH:
            case ApplicationRoleProvider::ADMIN_FONC:
            case ApplicationRoleProvider::OBSERVATEUR:
            case ApplicationRoleProvider::DRH:
            case EntretienProfessionnelRoleProvider::GESTIONNAIRE:
                $agents = $this->getAgentService()->getAgents();
                break;
            case AgentRoleProvider::ROLE_AGENT:
                $agents = [$agent];
                break;
            case AgentRoleProvider::ROLE_AUTORITE:
                $agents = $this->getAgentAutoriteService()->getAgentsWithAutoriteAndDate($agent, $now);
                break;
            case AgentRoleProvider::ROLE_SUPERIEURE:
                $agents = $this->getAgentSuperieurService()->getAgentsWithSuperieurAndDate($agent, $now);
                break;
            case StructureRoleProvider::OBSERVATEUR:
                $structures = $this->getStructureService()->getStructuresByObservateur($user, true);
                $agents = $this->getAgentService()->getAgentsByStructures($structures);
                break;
            case StructureRoleProvider::GESTIONNAIRE:
                $structures = $this->getStructureService()->getStructuresByGestionnaire($user, true);
                $agents = $this->getAgentService()->getAgentsByStructures($structures);
                break;
            case StructureRoleProvider::RESPONSABLE:
                $structures = $this->getStructureService()->getStructuresByResponsable($user, true);
                $agents = $this->getAgentService()->getAgentsByStructures($structures);
                break;
            case EntretienProfessionnelRoleProvider::OBSERVATEUR:
                $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByObservateur($user);
                $agents = array_map(function (EntretienProfessionnel $entretien) { return $entretien->getAgent(); }, $entretiens);
                break;
            default:
                $agents = [];
                break;
        }

        return $agents;
    }
}