<?php

namespace Application\Assertion;

use Agent\Entity\Db\AgentAffectation;
use Application\Entity\Db\Agent;
use Application\Provider\Privilege\AgentaffichagePrivileges;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\Perimetre\PerimetreServiceAwareTrait;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/** Note : pas besoin d'assertion de controller car cela n'est que de la gestion d'affichage sans routing */

class AgentAffichageAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use PerimetreServiceAwareTrait;
    use PrivilegeCategorieServiceAwareTrait;
    use PrivilegeServiceAwareTrait;


    /**
     * Exemple de ce que l'on pourrait faire avec l'utilisation de périmètre pour remplacer les calculs dans les assertions
     * TODO :: définir un moyen d'associé des types de périmètre aux rôles (p.e. STRUCTURE => Responsable de structure, AGENT => Supérieure hiérachique directe)
     * TODO :: provoquer que le calcul des perimètres utiles
     **/
    public function computePerimetreCompatible(UserInterface $user, RoleInterface $role, Agent $entity): bool
    {
        $perimetres = $this->getPerimetreService()->getPerimetres($user, $role);

        if (in_array($role->getRoleId(), [StructureRoleProvider::OBSERVATEUR, StructureRoleProvider::GESTIONNAIRE, StructureRoleProvider::RESPONSABLE])) {
            $relevantPerimetres = array_filter($perimetres, function (string $p) { return str_starts_with($p, 'STRUCTURE_'); });
            $relevantPerimetres = array_map(function (string $p) { return explode("_", $p)[1]; }, $relevantPerimetres);

            if (in_array('ALL', $relevantPerimetres)) {
                $intersection = ["ALL"];
            } else {
                $affectations = $entity->getAffectationsActifs();
                $structures = array_map(function (AgentAffectation $a) {
                    return $a->getStructure()->getId();
                }, $affectations);
                $intersection = array_intersect($relevantPerimetres, $structures);
            }
            return !empty($intersection);
        }

        return true;
    }


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
        $isObservateur = false;
        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE) $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($entity,$agent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($entity,$agent);
        if ($role->getRoleId() === StructureRoleProvider::OBSERVATEUR) $isObservateur = $this->getObservateurService()->isObservateur($structures,$user);

        switch ($privilege) {
            case AgentaffichagePrivileges::AGENTAFFICHAGE_COMPTE :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_AUTORITE :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_SUPERIEUR :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_CARRIERECOMPLETE :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_DATERESUME :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_AFFECTATION :
            case AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_STATUT :
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::OBSERVATEUR, AppRoleProvider::DRH => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    StructureRoleProvider::OBSERVATEUR => $isObservateur,
                    Agent::ROLE_SUPERIEURE => $isSuperieur,
                    Agent::ROLE_AUTORITE => $isAutorite,
                    AppRoleProvider::AGENT => $entity === $agent,
                    default => false,
                };
        }
        return true;
    }
}