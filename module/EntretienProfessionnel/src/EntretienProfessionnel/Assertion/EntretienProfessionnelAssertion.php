<?php

namespace EntretienProfessionnel\Assertion;

use Application\Entity\Db\Agent;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class EntretienProfessionnelAssertion extends AbstractAssertion {

    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use UserServiceAwareTrait;
    use StructureServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use PrivilegeCategorieServiceAwareTrait;

    private ?Agent $lastAgent = null;
    private ?EntretienProfessionnel $lastEntretien = null;
    private ?Role $lastRole = null;
    private ?array $predicats = null;

    /**
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @param Agent|null $connectedAgent
     * @param Role|null $role
     * @return array
     */
    private function computePredicats(?EntretienProfessionnel $entretienProfessionnel, ?Agent $connectedAgent, ?RoleInterface $role) : array
    {
        if (
            $this->lastAgent === $connectedAgent AND
            $this->lastEntretien === $entretienProfessionnel AND
            $this->lastRole === $role
        ) return $this->predicats;

//        var_dump("Recalcul des prédicats : ".(new \DateTime())->format('H:i:s:u '));
        $this->lastAgent = $connectedAgent;
        $this->lastEntretien = $entretienProfessionnel;
        $this->lastRole = $role;

        $structures = $entretienProfessionnel->getAgent()->getStructures();
        $this->predicats = [
            'isAgentEntretien'          => $entretienProfessionnel->isAgent($connectedAgent),
            'isResponsableEntretien'    => $entretienProfessionnel->isReponsable($connectedAgent),
            'isResponsableStructure'    => ($role->getRoleId() === RoleProvider::RESPONSABLE) && $this->getStructureService()->isResponsableS($structures, $connectedAgent),
            'isAutoriteStructure'       => $this->getStructureService()->isAutoriteS($structures, $connectedAgent),
            'isSuperieureHierarchique'  => $this->getAgentSuperieurService()->isSuperieur($entretienProfessionnel->getAgent(), $connectedAgent),
            'isAutoriteHierarchique'    => $this->getAgentAutoriteService()->isAutorite($entretienProfessionnel->getAgent(), $connectedAgent),
        ];
//        var_dump("Fin du calcul : ".(new \DateTime())->format('H:i:s:u '));
        return $this->predicats;
    }

    public function isScopeCompatible(EntretienProfessionnel $entretienProfessionnel, ?Agent $connectedAgent, ?RoleInterface $connectedRole, ?array $predicats = null): bool
    {
        if ($predicats === null) $predicats = $this->computePredicats($entretienProfessionnel, $connectedAgent, $connectedRole);
        return match ($connectedRole->getRoleId()) {
            AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::DRH, AppRoleProvider::OBSERVATEUR  => true,
            AppRoleProvider::AGENT => $predicats['isAgentEntretien'],
            RoleProvider::RESPONSABLE => $predicats['isResponsableStructure'],
            Agent::ROLE_SUPERIEURE => $predicats['isSuperieureHierarchique'],
            Agent::ROLE_AUTORITE => $predicats['isAutoriteHierarchique'],
            default => false,
        };
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {


        /** @var EntretienProfessionnel $entity */
        if (!$entity instanceof EntretienProfessionnel) {
            return false;
        }

        /** @var EntretienProfessionnel $entity */
        $role = $this->getUserService()->getConnectedRole();
        if ($role->getRoleId() === AppRoleProvider::ADMIN_TECH) return true;

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        //todo QUESTION : pourquoi devoir faire cela c'est pas normal !!!
        [$catCode, $priCode] = explode('-', $privilege);
        $categorie = $this->getPrivilegeCategorieService()->findByCode($catCode);
        $pprivilege = $this->getPrivilegeService()->findByCode($priCode, $categorie->getId());
        if ($pprivilege === null) return false;
        $listings = $pprivilege->getRoles()->toArray();
        if (!in_array($role, $listings)) return false;
        //todo FIN BIZARERIE ...

        $now = new DateTime();

        $grades = $entity->getAgent()->getGradesActifs($entity->getDateEntretien());
        $inhibition = false;
        foreach ($grades as $grade) {
            if ($grade->getCorps()->isSuperieurAsAutorite()) {
                $inhibition = true;
                break;
            }
        }

		$grades = $entity->getAgent()->getGradesActifs($entity->getDateEntretien());
        $inhibition = false;
		foreach ($grades as $grade) {
			if ($grade->getCorps()->isSuperieurAsAutorite()) {
				$inhibition = true;
				break;
			}
		}

        $predicats = $this->computePredicats($entity, $agent, $role);

        switch($privilege) {
            case EntretienproPrivileges::ENTRETIENPRO_RENSEIGNER :
                if (! $entity->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER)) return false;
                if (!$this->isScopeCompatible($entity, $agent, $role, $predicats)) return false;
                if ($role->getRoleId() === AppRoleProvider::AGENT) {
                    if ($now < $entity->getDateEntretien()) return false;
                    return ($entity->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER));
                }
                return true;
            case EntretienproPrivileges::ENTRETIENPRO_EXPORTER :
                if ($entity->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER) ||
                    $entity->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) return false;
                return $this->isScopeCompatible($entity, $agent, $role, $predicats);
            case EntretienproPrivileges::ENTRETIENPRO_CONVOQUER :
            case EntretienproPrivileges::ENTRETIENPRO_MODIFIER :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::DRH:
                    case AppRoleProvider::OBSERVATEUR:
                        return true;
                    case AppRoleProvider::AGENT:
                        if ($entity->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER)) return false;
                        if ($entity->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) return false;
                        return $predicats['isAgentEntretien'];
                    case RoleProvider::RESPONSABLE:
                        return $predicats['isResponsableStructure'];
                    case Agent::ROLE_SUPERIEURE:
                        return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE:
                        return $predicats['isAutoriteHierarchique'];
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_HISTORISER :
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH => true,
                    RoleProvider::RESPONSABLE => (($predicats['isResponsableStructure'] and $predicats['isResponsableEntretien']) or $predicats['isAutoriteStructure']),
                    Agent::ROLE_SUPERIEURE => $predicats['isSuperieureHierarchique'] and $predicats['isResponsableEntretien'],
                    Agent::ROLE_AUTORITE => $predicats['isAutoriteHierarchique'],
                    default => false,
                };
            case EntretienproPrivileges::ENTRETIENPRO_AFFICHER :
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_RESPONSABLE :
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_AGENT :
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_OBSERVATION :
                    return $this->isScopeCompatible($entity, $agent, $role, $predicats);
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_DRH :
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::DRH => true,
                    RoleProvider::RESPONSABLE, Agent::ROLE_AUTORITE => ($predicats['isAutoriteHierarchique'] && ($inhibition || !$predicats['isResponsableEntretien'])),
                    default => false,
                };
        }
        return true;
    }

    /**
     * @param AbstractActionController $controller
     * @param $action
     * @param $privilege
     * @return bool
     */
    protected function assertController($controller, $action = null, $privilege = null) : bool
    {
        /** @var EntretienProfessionnel $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $predicats = [];
        $entretien = null;
        if ($agent) {
            //to remove copium here
            $entretienId = (($this->getMvcEvent()->getRouteMatch()->getParam('entretien-professionnel')));
            if ($entretienId === null) $entretienId = (($this->getMvcEvent()->getRouteMatch()->getParam('entretien')));
            $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnel($entretienId);
            if ($entretien) $predicats = $this->computePredicats($entretien, $agent, $role);
        }

        if ($entretien === null) return true;
        switch ($action) {
            case 'exporter-crep' :
            case 'exporter-cref' :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_TECH :
                    case AppRoleProvider::ADMIN_FONC :
                    case AppRoleProvider::OBSERVATEUR :
                        return true;
                    case Agent::ROLE_AGENT :
                        if ($entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) return false;
                        if ($entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER)) return false;
                        return $predicats['isAgentEntretien'];
                    case RoleProvider::RESPONSABLE : return ($predicats['isResponsableStructure'] OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE : return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE : return $predicats['isAutoriteHierarchique'];
                }
                return false;
            case 'creer' :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_TECH :
                    case AppRoleProvider::ADMIN_FONC :
                    case RoleProvider::RESPONSABLE :
                    case Agent::ROLE_SUPERIEURE :
                    case Agent::ROLE_AUTORITE :
                        return true;
                }
                return false;
            case 'modifier' :
            case 'historiser' :
            case 'restaurer' :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_TECH :
                    case AppRoleProvider::ADMIN_FONC :
                        return true;
                    case RoleProvider::RESPONSABLE :  return ($predicats['isResponsableStructure'] OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE : return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE : return $predicats['isAutoriteHierarchique'];
                }
                return false;
            case 'acceder' :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_TECH :
                    case AppRoleProvider::ADMIN_FONC :
                        return true;
                    case Agent::ROLE_AGENT : return $predicats['isAgentEntretien'];
                    case RoleProvider::RESPONSABLE : return ($predicats['isResponsableEntretien'] OR $predicats['isResponsableStructure'] OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE : return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE :
                        return $predicats['isAutoriteHierarchique'];
                }
                return false;
        }
        return true;
    }
}
