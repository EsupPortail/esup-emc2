<?php

namespace Application\Assertion;

use Application\Entity\Db\Agent;
use Application\Provider\Privilege\AgentaffichagePrivileges;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/** Note : pas besoin d'assertion de controller car cela n'est que de la gestion d'affichage sans routing */

class AgentAffichageAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use PrivilegeCategorieServiceAwareTrait;
    use PrivilegeServiceAwareTrait;

    public function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        /** @var Agent $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        //todo QUESTION : pourquoi devoir faire cela c'est pas normal !!!
        [$catCode, $priCode] = explode('-', $privilege);
        $categorie = $this->getPrivilegeCategorieService()->findByCode($catCode);
        $pprivilege = $this->getPrivilegeService()->findByCode($priCode, $categorie->getId());
        if ($pprivilege === null) return false;
        $listings = $pprivilege->getRoles()->toArray();
        if (!in_array($role, $listings)) return false;
        //todo FIN BIZARERIE ...

        $structures = [];
        foreach ($entity->getAffectations() as $affectation) {
            $structures[] = $affectation->getStructure();
        }
        foreach ($entity->getStructuresForcees() as $structureAgentForce) {
            $structures[] = $structureAgentForce->getStructure();
        }

        $isResponsable = false;
        $isSuperieur = false;
        $isAutorite = false;
        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE) $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($entity,$agent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($entity,$agent);

        switch ($privilege) {
            case AgentaffichagePrivileges::AGENTAFFICHAGE_COMPTE :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_AUTORITE :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_SUPERIEUR :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_CARRIERECOMPLETE :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_DATERESUME :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_AFFECTATION :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_STATUT :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::OBSERVATEUR:
                        return true;
                    case StructureRoleProvider::RESPONSABLE:
                        return $isResponsable;
                    case Agent::ROLE_SUPERIEURE:
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE:
                        return $isAutorite;
                    case AppRoleProvider::AGENT :
                        return $entity === $agent;
                }
                return false;
        }
        return true;
    }
}