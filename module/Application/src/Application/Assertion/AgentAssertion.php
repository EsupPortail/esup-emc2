<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentServiceAwareTrait;
use Structure\Provider\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class AgentAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        /** @var Agent $entity */

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();


        $isGestionnaire = false;
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) {
            $structures = [];
            foreach ($entity->getGrades() as $grade) {
                $structures[] = $grade->getStructure();
            }
            foreach ($structures as $structure) {
                $isGestionnaire = $this->getStructureService()->isGestionnaire($structure, $agent);
                if ($isGestionnaire) break;
            }
        }
        $isResponsable = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $structures = [];
            foreach ($entity->getGrades() as $grade) {
                $structures[] = $grade->getStructure();
            }
            foreach ($structures as $structure) {
                $isResponsable = $this->getStructureService()->isResponsable($structure, $entity);
                if ($isResponsable) break;
            }
        }

        switch($privilege) {
            case AgentPrivileges::AGENT_ACQUIS_AFFICHER:
                case true;
            case AgentPrivileges::AGENT_ACQUIS_MODIFIER:
            case AgentPrivileges::AGENT_ELEMENT_AJOUTER:
            case AgentPrivileges::AGENT_ELEMENT_MODIFIER:
            case AgentPrivileges::AGENT_ELEMENT_HISTORISER:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
//                    case RoleConstant::PERSONNEL:
//                        return ($entity->getUtilisateur() === $user) AND $entity->hasEntretienEnCours();
                    case RoleProvider::GESTIONNAIRE:
                            return $isGestionnaire;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
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
            case AgentPrivileges::AGENT_ELEMENT_AJOUTER_EPRO:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                }
                return false;
        }

        return true;
    }
}