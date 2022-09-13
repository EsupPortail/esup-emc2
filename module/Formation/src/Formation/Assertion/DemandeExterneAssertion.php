<?php

namespace Formation\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Provider\Privilege\DemandeexternePrivileges;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Entity\Db\Structure;
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
            if ($roleId === Structure::ROLE_GESTIONNAIRE) $liste = $structure->getGestionnaires();
            if ($roleId === Structure::ROLE_RESPONSABLE) $liste = $structure->getResponsables();
            foreach ($liste as $agent) $agents[$agent->getId()] = $agent;
        }
        return $agents;
    }

    /**
     * @param DemandeExterne|null $demande
     * @param string $privilege
     * @return bool
     */
    private function computeAssertion(?DemandeExterne $demande, string $privilege) : bool
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $agent = $demande->getAgent();

        switch($role->getRoleId()) {
            case RoleConstant::ADMIN_TECH :
            case RoleConstant::ADMIN_FONC :
            case RoleConstant::DRH :
            case FormationRoles::GESTIONNAIRE_FORMATION :
                return true;
            case RoleConstant::PERSONNEL :
                return $agent->getUtilisateur() === $user;
            case Agent::ROLE_AUTORITE :
                $autorites = $this->getAgentService()->computeAutorites($agent);
                return DemandeExterneAssertion::userInAgents($user, $autorites);
            case Agent::ROLE_SUPERIEURE :
                $superieurs = $this->getAgentService()->computeSuperieures($agent);
                return DemandeExterneAssertion::userInAgents($user, $superieurs);
            case Structure::ROLE_RESPONSABLE :
                $structures = $this->getAgentService()->computesStructures($agent);
                $responsables = DemandeExterneAssertion::agentInStructures(Structure::ROLE_RESPONSABLE, $structures);
                return DemandeExterneAssertion::userInAgents($user, $responsables);
            case Structure::ROLE_GESTIONNAIRE :
                $structures = $this->getAgentService()->computesStructures($agent);
                $gestionnaires = DemandeExterneAssertion::agentInStructures(Structure::ROLE_GESTIONNAIRE, $structures);
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
        $demandeId = (($this->getMvcEvent()->getRouteMatch()->getParam('demande-externe')));
        $entity = $this->getDemandeExterneService()->getDemandeExterne($demandeId);

        switch($action) {
            case 'afficher' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_AFFICHER);
            case 'ajouter' :
                return $this->computeAssertion($entity, DemandeexternePrivileges::DEMANDEEXTERNE_AJOUTER);
            case 'modifier' :
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