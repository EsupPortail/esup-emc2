<?php

namespace Agent\Assertion;

use Agent\Provider\Privilege\AgentPrivileges;
use Agent\Provider\Role\RoleProvider;
use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Entity\Db\Agent;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use UserServiceAwareTrait;

    public function computeAssertion(?Agent $entity, string $privilege): bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        /** @var Agent $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $isResponsable = false;
        $isSuperieur = false;
        $isAutorite = false;
        $isObservateur = false;
        $isAgent = false;
        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE) {
            $structures = $entity->getStructures();
            $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        }
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($entity, $agent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($entity, $agent);
        if ($role->getRoleId() === StructureRoleProvider::OBSERVATEUR) {
            $structures = $entity->getStructures();
            $isObservateur = $this->getObservateurService()->isObservateur($structures, $user);
        }
        if ($role->getRoleId() === RoleProvider::ROLE_AGENT) $isAgent = ($agent === $entity);

        switch ($privilege) {
            case AgentPrivileges::AGENT_AFFICHER :
            case AgentPrivileges::AGENT_ACQUIS_AFFICHER:
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::OBSERVATEUR, AppRoleProvider::DRH => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    Agent::ROLE_SUPERIEURE => $isSuperieur,
                    Agent::ROLE_AUTORITE => $isAutorite,
                    StructureRoleProvider::OBSERVATEUR => $isObservateur,
                    AppRoleProvider::AGENT => $entity === $agent,
                    default => false,
                };
            case AgentPrivileges::AGENT_ACQUIS_MODIFIER:
            case AgentPrivileges::AGENT_ELEMENT_AJOUTER:
            case AgentPrivileges::AGENT_ELEMENT_MODIFIER:
            case AgentPrivileges::AGENT_ELEMENT_HISTORISER:
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    Agent::ROLE_SUPERIEURE => $isSuperieur,
                    Agent::ROLE_AUTORITE => $isAutorite,
                    RoleProvider::ROLE_AGENT => $isAgent,
                    default => false,
                };
            case AgentPrivileges::AGENT_ELEMENT_DETRUIRE:
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH => true,
                    default => false,
                };
            case AgentPrivileges::AGENT_ELEMENT_VALIDER:
            case AgentPrivileges::AGENT_ELEMENT_AJOUTER_EPRO:
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    default => false,
                };
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }
        return $this->computeAssertion($entity, $privilege);
    }

    /**
     * @param AbstractActionController $controller
     * @param $action
     * @param $privilege
     * @return bool
     */
    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var Agent|null $entity */
        $agentId = (($this->getMvcEvent()->getRouteMatch()->getParam('agent')));
        $entity = $this->getAgentService()->getAgent($agentId);

        if ($entity === null) {
            $user = $this->getUserService()->getConnectedUser();
            $entity = $this->getAgentService()->getAgentByUser($user);
        }

        return match ($action) {
            'informations', 'portfolio',
            'afficher-statuts-grades'
            => $this->computeAssertion($entity, AgentPrivileges::AGENT_AFFICHER),
            'upload-fichier'
            => $this->computeAssertion($entity, AgentPrivileges::AGENT_ELEMENT_AJOUTER),
            default => true,
        };
    }
}
