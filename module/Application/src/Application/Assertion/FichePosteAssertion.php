<?php

namespace Application\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Entity\Db\FichePoste;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Structure\Provider\RoleProvider;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class FichePosteAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use FichePosteServiceAwareTrait;
//    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        /** @var  */
        if (! $entity instanceof FichePoste) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

//        $agentFiche = $entity->getAgent();
//        $agentConnecte = $this->getAgentService()->getAgentByUser($user);

        $isGestionnaire = false;
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) {
            $isGestionnaire = $this->getFichePosteService()->isGererPar($entity, $user);
        }
        $isResponsable = false;
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $isResponsable = $this->getFichePosteService()->isGererPar($entity, $user);
        }

        switch($privilege) {

            case FichePostePrivileges::FICHEPOSTE_AFFICHER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::OBSERVATEUR:
                    case RoleConstant::DRH:
                        return true;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    case Agent::ROLE_SUPERIEURE:
                        $agent = $entity->getAgent();
                        $superieur = $this->getAgentService()->getAgentByUser($user);
                        $isSuperieur = $agent->hasSuperieurHierarchique($superieur);
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE:
                        $agent = $entity->getAgent();
                        $autorite = $this->getAgentService()->getAgentByUser($user);
                        $isAutorite = $agent->hasAutoriteHierarchique($autorite);
                        return $isAutorite;
                    case RoleConstant::PERSONNEL:
                        $isAgent = ($entity->getAgent()->getUtilisateur() === $user);
                        return $isAgent;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_AJOUTER :
            case FichePostePrivileges::FICHEPOSTE_MODIFIER :
            case FichePostePrivileges::FICHEPOSTE_HISTORISER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::DRH:
                        return true;
                    case RoleProvider::GESTIONNAIRE:
                        return $isGestionnaire;
                    case RoleProvider::RESPONSABLE:
                        return $isResponsable;
                    case Agent::ROLE_SUPERIEURE:
                        $agent = $entity->getAgent();
                        $superieur = $this->getAgentService()->getAgentByUser($user);
                        $isSuperieur = $agent->hasSuperieurHierarchique($superieur);
                        return $isSuperieur;
                    case Agent::ROLE_AUTORITE:
                        $agent = $entity->getAgent();
                        $autorite = $this->getAgentService()->getAgentByUser($user);
                        $isAutorite = $agent->hasAutoriteHierarchique($autorite);
                        return $isAutorite;
                    default:
                        return false;
                }
            case FichePostePrivileges::FICHEPOSTE_DETRUIRE :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::DRH:
                        return true;
                    default:
                        return false;
                }
        }

        return true;
    }


}