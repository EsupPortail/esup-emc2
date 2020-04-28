<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class AgentAssertion extends AbstractAssertion {
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $isGestionnaire = false;
        if ($role->getRoleId === RoleConstant::GESTIONNAIRE) {
            $structures = [];
            foreach ($entity->getGrades() as $grade) {
                $structures[] = $grade->getStructure();
            }
            foreach ($structures as $structure) {
                $isGestionnaire = $this->getStructureService()->isGestionnaire($structure, $user);
                if ($isGestionnaire) break;
            }
        }

        $isResponsable = false;
        if ($role->getRoleId === RoleConstant::RESPONSABLE_EPRO) {
            //todo
        }

        switch($privilege) {
            case AgentPrivileges::AGENT_ELEMENT_VOIR:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                        return true;
                    case RoleConstant::PERSONNEL:
                        return ($entity->getUtilisateur() === $user);
                    case RoleConstant::GESTIONNAIRE:
                        return $isGestionnaire;
                }
                return false;
            case AgentPrivileges::AGENT_ELEMENT_AJOUTER:
            case AgentPrivileges::AGENT_ELEMENT_MODIFIER:
            case AgentPrivileges::AGENT_ELEMENT_HISTORISER:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::PERSONNEL:
                        return ($entity->getUtilisateur() === $user);
                    case RoleConstant::GESTIONNAIRE:
                            return $isGestionnaire;
                }
                return false;
            case AgentPrivileges::AGENT_ELEMENT_DETRUIRE:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                }
                return false;
            case AgentPrivileges::AGENT_ELEMENT_VALIDER:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleConstant::RESPONSABLE_EPRO:
                        return  $isResponsable;
                }
                return false;
        }

        return true;
    }
}