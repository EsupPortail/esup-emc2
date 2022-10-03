<?php

namespace Formation\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Privilege\DemandeexternePrivileges;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Entity\Db\Structure;
use Structure\Provider\Role\RoleProvider;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class DemandeExterneAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param UserInterface $user
     * @param Agent[] $agents
     * @return bool
     */
    public static function userInAgents(UserInterface $user, array $agents) : bool
    {
        $users = array_map(function (Agent $a) { return $a->getUtilisateur(); }, $agents);
        return in_array($user, $users);
    }

    /**
     * @param string $roleId
     * @param Structure[] $structures
     * @return Agent[]
     */
    public static function agentInStructures(string $roleId, array $structures) : array
    {
        $agents = [];
        foreach ($structures as $structure) {
            $liste = [];
            if ($roleId === RoleProvider::GESTIONNAIRE) $liste = $structure->getGestionnaires();
            if ($roleId === RoleProvider::RESPONSABLE) $liste = $structure->getResponsables();
            foreach ($liste as $agent) $agents[$agent->getId()] = $agent;
        }
        $agents = array_map(function ($a) { return $a->getAgent();}, $agents);
        return $agents;
    }

    /**
     * @param DemandeExterne|null $demande
     * @param string $privilege
     * @return bool
     */
    private function computeAssertion(?DemandeExterne $demande, string $privilege, ?Agent $givenAgent = null) : bool
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $agent = ($demande)?$demande->getAgent():$givenAgent;

        switch($role->getRoleId()) {
            case RoleConstant::ADMIN_TECH :
            case RoleConstant::ADMIN_FONC :
            case RoleConstant::DRH :
            case FormationRoles::GESTIONNAIRE_FORMATION :
                return true;
            case RoleConstant::PERSONNEL :
                if ($privilege === DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER AND $demande->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS) return false;
                return $agent->getUtilisateur() === $user;
            case Agent::ROLE_AUTORITE :
                if ($privilege === DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER AND $demande->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS) return false;
                $autorites = $this->getAgentService()->computeAutorites($agent);
                return DemandeExterneAssertion::userInAgents($user, $autorites);
            case Agent::ROLE_SUPERIEURE :
                if ($privilege === DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER AND $demande->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS) return false;
                $superieurs = $this->getAgentService()->computeSuperieures($agent);
                return DemandeExterneAssertion::userInAgents($user, $superieurs);
            case RoleProvider::RESPONSABLE :
                if ($privilege === DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER AND $demande->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS) return false;
                $structures = $this->getAgentService()->computesStructures($agent);
                $responsables = DemandeExterneAssertion::agentInStructures(RoleProvider::RESPONSABLE, $structures);
                return DemandeExterneAssertion::userInAgents($user, $responsables);
            case RoleProvider::GESTIONNAIRE :
                if ($privilege === DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER AND $demande->getEtat()->getCode() !== DemandeExterneEtats::ETAT_CREATION_EN_COURS) return false;
                $structures = $this->getAgentService()->computesStructures($agent);
                $gestionnaires = DemandeExterneAssertion::agentInStructures(RoleProvider::GESTIONNAIRE, $structures);
                return DemandeExterneAssertion::userInAgents($user, $gestionnaires);
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        /** @var DemandeExterne|null $entity */
        if (!$entity instanceof DemandeExterne) {
            return false;
        }

        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var DemandeExterne|null $entity */
        if ($this->getMvcEvent()->getRouteMatch() === null) return false;
        $demandeId = (($this->getMvcEvent()->getRouteMatch()->getParam('demande-externe')));
        $entity = $this->getDemandeExterneService()->getDemandeExterne($demandeId);
        /** @var Agent|null $entity */
        $agentId = (($this->getMvcEvent()->getRouteMatch()->getParam('agent')));
        $entityAgent = $this->getAgentService()->getAgent($agentId);

        switch($action) {
            case 'afficher' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_AFFICHER);
            case 'ajouter' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_AJOUTER, $entityAgent);
            case 'modifier' :
            case 'ajouter-devis' :
            case 'retirer-devis' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER);
            case 'historiser' :
            case 'restaurer' :
            return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_HISTORISER);
            case 'supprimer' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_SUPPRIMER);
            case 'valider-agent' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_AGENT);
            case 'valider-responsable' :
            case 'refuser-responsable' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_RESPONSABLE);
            case 'valider-drh' :
            case 'refuser-drh' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_DRH);
        }
        return true;
    }
}