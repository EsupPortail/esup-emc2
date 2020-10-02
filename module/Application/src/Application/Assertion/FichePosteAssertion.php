<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\FichePoste;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class FichePosteAssertion extends AbstractAssertion {
    use FichePosteServiceAwareTrait;
//    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        /** @var  */
        if (! $entity instanceof FichePoste) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $isGestionnaire = false;
        if ($role->getRoleId() === RoleConstant::GESTIONNAIRE || $role->getRoleId() === RoleConstant::RESPONSABLE) {
            $isGestionnaire = $this->getFichePosteService()->isGererPar($entity, $user);
        }

        switch($privilege) {

            case FichePostePrivileges::FICHEPOSTE_AFFICHER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                    case RoleConstant::RESPONSABLE:
                        return $isGestionnaire;
                    case RoleConstant::RESPONSABLE_EPRO:
                        //TODO return false;
                    case RoleConstant::PERSONNEL:
                        $isAgent = ($entity->getAgent()->getUtilisateur() === $user);
                        return $isAgent;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_MODIFIER :
            case FichePostePrivileges::FICHEPOSTE_HISTORISER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                    case RoleConstant::RESPONSABLE:
                        return $isGestionnaire;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_DETRUIRE :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    default:
                        return false;
                }
        }

        return true;
    }


}