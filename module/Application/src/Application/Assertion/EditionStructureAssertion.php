<?php

namespace Application\Assertion;

use Application\Controller\StructureController;
use Application\Entity\Db\Structure;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class EditionStructureAssertion extends AbstractAssertion {
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null) {
        if ($entity instanceof Structure) {
            return true;
        }
        return false;
    }

    protected function assertController($controller, $action = null, $privilege = null) {

        $role = $this->getUserService()->getConnectedRole();

        if ($controller === StructureController::class) {

            if ($role->getRoleId() === Role::ADMIN_FONC OR $role->getRoleId() === Role::ADMIN_TECH) {
                return false;
            }

            switch($action) {
                case 'editer-description' :
                    if ($role->getRoleId() === Role::GESTIONNAIRE) {
                        return true;
                    }
                    return false;
                default:
                    return true;
            }
        }
        return false;
    }
}