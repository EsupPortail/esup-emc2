<?php

namespace Structure\Assertion;

use Application\Constant\RoleConstant;
use Application\Service\Agent\AgentServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Provider\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class StructureAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use PrivilegeServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        if (! $entity instanceof Structure) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        /** @var RoleInterface $role */
        $role = $this->getUserService()->getConnectedRole();

        $isGestionnaire = false;
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) {
            $isGestionnaire = $this->getStructureService()->isGestionnaire($entity, $agent);
        }
        $isResponsable = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $isResponsable = $this->getStructureService()->isResponsable($entity, $agent);
        }

        switch($privilege) {
            case StructurePrivileges::STRUCTURE_AFFICHER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                    case RoleConstant::DRH:
                        return true;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }

            case StructurePrivileges::STRUCTURE_DESCRIPTION:
            case StructurePrivileges::STRUCTURE_GESTIONNAIRE:
            case StructurePrivileges::STRUCTURE_COMPLEMENT_AGENT:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::DRH:
                        return true;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }

        }

        return true;
    }


}