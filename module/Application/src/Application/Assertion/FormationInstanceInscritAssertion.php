<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Formation\Entity\Db\FormationInstanceInscrit;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\FormationPrivileges;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class FormationInstanceInscritAssertion extends AbstractAssertion
{
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        if (!$entity instanceof FormationInstanceInscrit) {
            return false;
        }

        /** @var Agent $entity */

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $isGestionnaire = false;
        if ($role->getRoleId() === RoleConstant::GESTIONNAIRE) {
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
        if ($role->getRoleId() === RoleConstant::RESPONSABLE) {
            $structures = [];
            foreach ($entity->getGrades() as $grade) {
                $structures[] = $grade->getStructure();
            }
            foreach ($structures as $structure) {
                $isResponsable = $this->getStructureService()->isResponsable($structure, $user);
                if ($isResponsable) break;
            }
        }

        switch ($privilege) {
            case FormationPrivileges::FORMATION_INSTANCE_QUESTIONNAIRE_VISUALISER:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                    case RoleConstant::DRH:
                        return true;
                    case RoleConstant::RESPONSABLE:
                        return $isResponsable;
                    case RoleConstant::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleConstant::PERSONNEL:
                        return ($entity->getAgent()->getUtilisateur() !== null and $user === $entity->getAgent()->getUtilisateur());
                    default :
                        return false;
                }
            case FormationPrivileges::FORMATION_INSTANCE_QUESTIONNAIRE_RENSEIGNER:
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::PERSONNEL:
                        return ($entity->getAgent()->getUtilisateur() !== null and $user === $entity->getAgent()->getUtilisateur());
                    default :
                        return false;
                }
        }

        return false;
    }
}

