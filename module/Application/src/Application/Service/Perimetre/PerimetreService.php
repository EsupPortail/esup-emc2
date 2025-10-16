<?php

namespace Application\Service\Perimetre;

use Application\Entity\Db\Agent;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenIndicateur\Service\Perimetre\PerimetreServiceInterface;
use UnicaenIndicateur\Service\Perimetre\PerimetreServiceTrait;
use UnicaenUtilisateur\Entity\Db\AbstractRole;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use ZfcUser\Entity\UserInterface;

class PerimetreService implements PerimetreServiceInterface
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use PerimetreServiceTrait;
    use StructureServiceAwareTrait;

    /**
     * @param AbstractUser $user
     * @param AbstractRole $role
     * @return array
     */
    public function getPerimetres(UserInterface $user, RoleInterface $role): array
    {
        $perimetres = [];

        /** Périmètres de structure $structures ***********************************************************************/
        $structures = [];
        switch ($role->getRoleId()) {
            case AppRoleProvider::ADMIN_TECH:
            case AppRoleProvider::ADMIN_FONC:
            case AppRoleProvider::OBSERVATEUR:
            case AppRoleProvider::DRH:
                $structures = true;
                break;
            case StructureRoleProvider::RESPONSABLE:
                $listing = $this->getStructureService()->getStructuresByResponsable($user);
                foreach ($listing as $structure) {
                    $all = $this->getStructureService()->getStructuresFilles($structure, true);
                    foreach ($all as $item) $structures[$item->getId()] = $item;
                }
                break;
            case StructureRoleProvider::GESTIONNAIRE:
                $listing = $this->getStructureService()->getStructuresByGestionnaire($user);
                foreach ($listing as $structure) {
                    $all = $this->getStructureService()->getStructuresFilles($structure, true);
                    foreach ($all as $item) $structures[$item->getId()] = $item;
                }
                break;
            case StructureRoleProvider::OBSERVATEUR:
                $listing = $this->getStructureService()->getStructuresByObservateur($user);
                foreach ($listing as $structure) {
                    $all = $this->getStructureService()->getStructuresFilles($structure, true);
                    foreach ($all as $item) $structures[$item->getId()] = $item;
                }
                break;
        }
        if ($structures !== true) $structures = array_map(function (Structure $s) {
            return 'STRUCTURE_' . $s->getId();
        }, $structures);
        else $structures = ["STRUCTURE_ALL"];
        $perimetres = array_merge($perimetres, $structures);

        /** Périmètres d'agent $agents ********************************************************************************/
        $agents = [];
        switch ($role->getRoleId()) {
            case AppRoleProvider::ADMIN_TECH:
            case AppRoleProvider::ADMIN_FONC:
            case AppRoleProvider::OBSERVATEUR:
            case AppRoleProvider::DRH:
                $agents = true;
                break;
            case Agent::ROLE_AUTORITE:
                $agent = $this->getAgentService()->getAgentByConnectedUser();
                $listing = $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent);
                foreach ($listing as $chaine) {
                    $agents[$chaine->getAgent()->getId()] = $chaine->getAgent();
                }
                break;
            case Agent::ROLE_SUPERIEURE:
                $agent = $this->getAgentService()->getAgentByConnectedUser();
                $listing = $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent);
                foreach ($listing as $chaine) {
                    $agents[$chaine->getAgent()->getId()] = $chaine->getAgent();
                }
                break;
            case StructureRoleProvider::RESPONSABLE:
                $structures = $this->getStructureService()->getStructuresByResponsable($user);
                $listing = $this->getAgentService()->getAgentsByStructures($structures);
                foreach ($listing as $item) {
                    $agents[$item->getId()] = $item;
                }
                break;
            case StructureRoleProvider::OBSERVATEUR:
                $structures = $this->getStructureService()->getStructuresByObservateur($user);
                $listing = $this->getAgentService()->getAgentsByStructures($structures);
                foreach ($listing as $item) {
                    $agents[$item->getId()] = $item;
                }
                break;
        }
        if ($agents !== true) $agents = array_map(function (Agent $a) {
            return 'AGENT_' . $a->getId();
        }, $agents);
        else $agents = ["AGENT_ALL"];
        $perimetres = array_merge($perimetres, $agents);

        //ROLE
        $roles = [$role];
        $roles = array_map(function (Role $s) {
            return 'ROLE_' . $s->getId();
        }, $roles);
        $perimetres = array_merge($perimetres, $roles);


        return $perimetres;
    }

}