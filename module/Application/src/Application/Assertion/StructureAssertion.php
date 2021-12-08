<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Structure;
use Application\Provider\Privilege\StructurePrivileges;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class StructureAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        if (! $entity instanceof Structure) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $isGestionnaire = false;
        if ($role->getRoleId() === RoleConstant::GESTIONNAIRE) {
            $isGestionnaire = $this->getStructureService()->isGestionnaire($entity, $user);
        }
        $isResponsable = false;
        if ($role->getRoleId() === RoleConstant::RESPONSABLE) {
            $isResponsable = $this->getStructureService()->isResponsable($entity, $agent);
        }

        switch($privilege) {

            case StructurePrivileges::STRUCTURE_AFFICHER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleConstant::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }

            case StructurePrivileges::STRUCTURE_DESCRIPTION:
            case StructurePrivileges::STRUCTURE_GESTIONNAIRE:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleConstant::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }

        }

        return true;
    }


}