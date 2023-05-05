<?php

namespace Structure\Assertion;

use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class StructureAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use PrivilegeServiceAwareTrait;

    public function computeAssertion(?Structure $entity, string $privilege) : bool
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $isResponsable = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $isResponsable = $this->getStructureService()->isResponsable($entity, $agent);
        }

        switch($privilege) {
            case StructurePrivileges::STRUCTURE_AFFICHER :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::OBSERVATEUR:
                    case AppRoleProvider::DRH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }
            case StructurePrivileges::STRUCTURE_DESCRIPTION:
            case StructurePrivileges::STRUCTURE_GESTIONNAIRE:
            case StructurePrivileges::STRUCTURE_COMPLEMENT_AGENT:
            case StructurePrivileges::STRUCTURE_AGENT_FORCE:
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::DRH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }
        }
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        if (! $entity instanceof Structure) {
            return false;
        }
        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var Structure|null $entity */
        $structureId = (($this->getMvcEvent()->getRouteMatch()->getParam('structure')));
        $entity = $this->getStructureService()->getStructure($structureId);

        switch($action) {
            case 'afficher' :
                return $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_AFFICHER);
            case 'editer-description' :
            case 'toggle-resume-mere' :
                return $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_DESCRIPTION);
            case 'ajouter-gestionnaire' :
            case 'retirer-gestionnaire' :
            case 'ajouter-responsable' :
            case 'retirer-responsable' :
                return $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_GESTIONNAIRE);
            case 'ajouter-manuellement-agent' :
            case 'retirer-manuellement-agent' :
                return  $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_AGENT_FORCE);
        }
        return true;
    }
}