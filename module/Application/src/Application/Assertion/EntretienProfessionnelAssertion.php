<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Entity\Db\EntretienProfessionnel;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\EntretienproPrivileges;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class EntretienProfessionnelAssertion extends AbstractAssertion {

    use UserServiceAwareTrait;
    use StructureServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        if (!$entity instanceof EntretienProfessionnel) {
            return false;
        }

        /** @var EntretienProfessionnel $entity */
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $isResponsable = false;
        if ($role->getRoleId() === RoleConstant::RESPONSABLE) {
            $structures = [];
            foreach ($entity->getAgent()->getGrades() as $grade) {
                $structures[] = $grade->getStructure();
            }
            foreach ($entity->getAgent()->getStructuresForcees() as $structuresForcee) {
                $structures[] = $structuresForcee->getStructure();
            }
            foreach ($structures as $structure) {
                $isResponsable = $this->getStructureService()->isResponsable($structure, $user);
                if ($isResponsable) break;
            }
        }

        switch($privilege) {
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_AGENT :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::PERSONNEL:
                        return $entity->getAgent()->getUtilisateur() === $user;
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_RESPONSABLE :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::GESTIONNAIRE:
                        return $entity->getResponsable() === $user;
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_DRH :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::DRH:
                        return true;
                    case RoleConstant::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }
        }
        return true;
    }
}