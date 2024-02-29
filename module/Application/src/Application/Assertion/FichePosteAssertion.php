<?php

namespace Application\Assertion;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FichePoste;
use FichePoste\Provider\Etat\FichePosteEtats;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Structure\Provider\Role\RoleProvider;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class FichePosteAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;

    use FichePosteServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        /** @var  */
        if (! $entity instanceof FichePoste) {
            return false;
        }

        return true;


        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $connectedAgent = $this->getAgentService()->getAgentByUser($user);
        $referencedAgent = $entity->getAgent();

        $isResponsable = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $isResponsable = $this->getFichePosteService()->isGererPar($entity, $user);
        }
        $isSuperieur = false; $isAutorite = false;
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($referencedAgent,$connectedAgent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($referencedAgent,$connectedAgent);

        $etatCode = ($entity->getEtatActif())?$entity->getEtatActif()->getType()->getCode():null;
        switch($privilege) {

            case FichePostePrivileges::FICHEPOSTE_AFFICHER :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::OBSERVATEUR:
                    case AppRoleProvider::DRH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    case Agent::ROLE_SUPERIEURE:
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE:
                        return $isAutorite;
                    case AppRoleProvider::AGENT:
                        $isAgent = ($entity->getAgent()->getUtilisateur() === $user);
                        return $isAgent AND ($etatCode === FichePosteEtats::ETAT_CODE_OK OR $etatCode === FichePosteEtats::ETAT_CODE_SIGNEE);
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_AJOUTER :
            case FichePostePrivileges::FICHEPOSTE_MODIFIER :
            case FichePostePrivileges::FICHEPOSTE_HISTORISER :
                // REMARQUE on ne peut plus agir sur une fiche signée et plus active
                if ($etatCode === FichePosteEtats::ETAT_CODE_SIGNEE) return false;
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::DRH:
                        return true;
                    case Agent::ROLE_SUPERIEURE:
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE:
                        return $isAutorite;
                    case RoleProvider::RESPONSABLE :
                        return $isResponsable;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_DETRUIRE :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::DRH:
                        return true;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_ETAT :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                        return true;
                    default :
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_VALIDER_RESPONSABLE :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                        return true;
                    case Agent::ROLE_AUTORITE:
                        return $isAutorite;
                    case Agent::ROLE_SUPERIEURE:
                        return $isSuperieur;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_VALIDER_AGENT :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                        return true;
                    case AppRoleProvider::AGENT:
                        $isAgent = ($entity->getAgent()->getUtilisateur() === $user);
                        return $isAgent;
                    default:
                        return false;
                }
        }
        return true;
    }
}