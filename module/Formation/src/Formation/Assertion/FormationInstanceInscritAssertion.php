<?php

namespace Formation\Assertion;

use Application\Entity\Db\Agent;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Provider\Privilege\FormationPrivileges;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class FormationInstanceInscritAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (!$entity instanceof FormationInstanceInscrit) {
            return false;
        }

        /** @var Agent $entity */

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $isGestionnaire = false;
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) {
            $structures = [];
            foreach ($entity->getGrades() as $grade) {
                $structures[] = $grade->getStructure();
            }
            foreach ($structures as $structure) {
                $isGestionnaire = $this->getStructureService()->isGestionnaire($structure, $entity);
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

        switch ($privilege) {
            case FormationPrivileges::FORMATION_QUESTIONNAIRE_VISUALISER:
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::OBSERVATEUR:
                    case AppRoleProvider::DRH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case AppRoleProvider::AGENT:
                        return ($entity->getAgent()->getUtilisateur() !== null and $user === $entity->getAgent()->getUtilisateur());
                    default :
                        return false;
                }
            case FormationPrivileges::FORMATION_QUESTIONNAIRE_MODIFIER:
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                        return true;
                    case AppRoleProvider::AGENT:
                        return ($entity->getAgent()->getUtilisateur() !== null and $user === $entity->getAgent()->getUtilisateur());
                    default :
                        return false;
                }
        }

        return true;
    }
}

