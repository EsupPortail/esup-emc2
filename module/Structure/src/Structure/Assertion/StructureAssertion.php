<?php

namespace Structure\Assertion;

use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class StructureAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use ObservateurServiceAwareTrait;
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
        $isObservateur = false;
        if ($role->getRoleId() === RoleProvider::OBSERVATEUR) {
            $isObservateur = $this->getObservateurService()->isObservateur([$entity], $user);
        }

        return match ($privilege) {
            StructurePrivileges::STRUCTURE_AFFICHER => match ($role->getRoleId()) {
                AppRoleProvider::ADMIN_FONC,
                AppRoleProvider::ADMIN_TECH,
                AppRoleProvider::OBSERVATEUR,
                AppRoleProvider::DRH
                            => true,
                RoleProvider::RESPONSABLE
                            => $isResponsable,
                RoleProvider::OBSERVATEUR
                => $isObservateur,
                default
                            => false,
            },
            StructurePrivileges::STRUCTURE_DESCRIPTION,
            StructurePrivileges::STRUCTURE_GESTIONNAIRE,
            StructurePrivileges::STRUCTURE_COMPLEMENT_AGENT,
            StructurePrivileges::STRUCTURE_AGENT_FORCE,
            StructurePrivileges::STRUCTURE_AGENT_MASQUE,
                        => match ($role->getRoleId()) {
                            AppRoleProvider::ADMIN_FONC,
                            AppRoleProvider::ADMIN_TECH,
                            AppRoleProvider::DRH
                                            => true,
                            RoleProvider::RESPONSABLE
                                            => $isResponsable,
                            default
                                            => false,
            },
            default
                        => true,
        };
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

        return match ($action) {
            'afficher'
                    => $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_AFFICHER),
            'editer-description',
            'toggle-resume-mere'
                    => $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_DESCRIPTION),
            'ajouter-gestionnaire',
            'retirer-gestionnaire',
            'ajouter-responsable',
            'retirer-responsable'
                    => $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_GESTIONNAIRE),
            'ajouter-manuellement-agent',
            'retirer-manuellement-agent'
                    => $this->computeAssertion($entity, StructurePrivileges::STRUCTURE_AGENT_FORCE),
            default => true,
        };
    }
}