<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Structure;
use Application\Provider\Privilege\StructurePrivileges;
use Application\Service\Structure\StructureService;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class StructureAssertion extends AbstractAssertion {
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        if (! $entity instanceof Structure) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        switch($privilege) {

            case StructurePrivileges::STRUCTURE_AFFICHER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                        $isGestionnaire = $this->getStructureService()->isGestionnaire($entity, $user);
                        return $isGestionnaire;
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
                        $isGestionnaire = $this->getStructureService()->isGestionnaire($entity, $user);
                        return $isGestionnaire;
                    default:
                        return false;
                }

        }

        return true;
    }


}