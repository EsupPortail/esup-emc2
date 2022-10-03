<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class AgentAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        /** @var Agent $entity */

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();


        $structures = [];
        foreach ($entity->getAffectations() as $affectation) {
            $structures[] = $affectation->getStructure();
        }
        foreach ($entity->getStructuresForcees(false) as $structureAgentForce) {
            $structures[] = $structureAgentForce->getStructure();
        }

        $isResponsable = false;
        $isGestionnaire = false;
        $isSuperieur = false;
        $isAutorite = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) $isGestionnaire = $this->getStructureService()->isGestionnaireS($structures, $agent);
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $entity->hasSuperieurHierarchique($agent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $entity->hasAutoriteHierarchique($agent);

        switch ($privilege) {
            case AgentPrivileges::AGENT_AFFICHER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case Agent::ROLE_SUPERIEURE:
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE:
                        return $isAutorite;
                    case Agent::ROLE_AGENT :
                        return $entity === $agent;
                }
                return false;
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
                    case Agent::ROLE_SUPERIEURE;
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE;
                        return $isAutorite;
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

    /**
     * @param AbstractActionController $controller
     * @param $action
     * @param $privilege
     * @return bool
     */
    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        /** @var Agent|null $entity */
        $entity = null;
//        if (true AND $agent) {
            $agentId = (($this->getMvcEvent()->getRouteMatch()->getParam('agent')));
            $entity = $this->getAgentService()->getAgent($agentId);
//        }

        $structures = [];
        $affectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($entity);
        foreach ($affectations as $affectation) {
            $structures[] = $affectation->getStructure();
        }
        foreach ($entity->getStructuresForcees(false) as $structureAgentForce) {
            $structures[] = $structureAgentForce->getStructure();
        }

        $isResponsable = false;
        $isGestionnaire = false;
        $isSuperieur = false;
        $isAutorite = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) $isGestionnaire = $this->getStructureService()->isGestionnaireS($structures, $agent);
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $entity->hasSuperieurHierarchique($agent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $entity->hasAutoriteHierarchique($agent);

        switch ($action) {
            case 'afficher' :
            case 'afficher-statuts-grades' :
            switch ($role->getRoleId()) {
                case RoleConstant::ADMIN_TECH :
                case RoleConstant::ADMIN_FONC :
                case RoleConstant::OBSERVATEUR :
                    return true;
                case RoleProvider::RESPONSABLE:
                    return $isResponsable;
                case RoleProvider::GESTIONNAIRE:
                    return $isGestionnaire;
                case Agent::ROLE_SUPERIEURE:
                    return $isSuperieur;
                case Agent::ROLE_AUTORITE:
                    return $isAutorite;
                case Agent::ROLE_AGENT :
                    return $entity === $agent;
            }
            return false;
        }
        return true;
    }
}