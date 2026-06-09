<?php

namespace Agent\Assertion;

use Agent\Controller\PortfolioControler;
use Agent\Provider\Privilege\AgentPrivileges;
use Agent\Provider\Privilege\PortfolioPrivileges;
use Agent\Provider\Role\RoleProvider as AgentRoleProvider;
use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Entity\Db\Agent;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class PortfolioAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use UserServiceAwareTrait;

    public function computeAssertion(?Agent $entity, string $privilege): bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        /** @var Agent $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $isResponsable = false;
        $isSuperieur = false;
        $isAutorite = false;
        $isObservateur = false;
        $isAgent = false;
        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE) {
            $structures = $entity->getStructures();
            $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        }
        if ($role->getRoleId() === AgentRoleProvider::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($entity, $agent);
        if ($role->getRoleId() === AgentRoleProvider::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($entity, $agent);
        if ($role->getRoleId() === StructureRoleProvider::OBSERVATEUR) {
            $structures = $entity->getStructures();
            $isObservateur = $this->getObservateurService()->isObservateur($structures, $user);
        }
        if ($role->getRoleId() === AgentRoleProvider::ROLE_AGENT) $isAgent = ($agent === $entity);

        switch ($privilege) {
            case PortfolioPrivileges::PORTFOLIO_AFFICHER:
            case PortfolioPrivileges::PORTFOLIO_AFFICHER_DOCUMENT:
            case PortfolioPrivileges::PORTFOLIO_HISTORISER_DOCUMENT:
            case PortfolioPrivileges::PORTFOLIO_RESTAURER_DOCUMENT:
            case PortfolioPrivileges::PORTFOLIO_SUPPRIMER_DOCUMENT:
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    AgentRoleProvider::ROLE_SUPERIEURE => $isSuperieur,
                    AgentRoleProvider::ROLE_AUTORITE => $isAutorite,
                    AgentRoleProvider::ROLE_AGENT => $isAgent,
                    default => false,
                };
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (!$entity instanceof Agent) {
            return false;
        }
        return $this->computeAssertion($entity, $privilege);
    }

    /**
     * @param AbstractActionController $controller
     * @param $action
     * @param $privilege
     * @return bool
     */
    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var Agent|null $entity */
        $agentId = (($this->getMvcEvent()->getRouteMatch()->getParam('agent')));
        $entity = $this->getAgentService()->getAgent($agentId);

        if ($entity === null) {
            $user = $this->getUserService()->getConnectedUser();
            $entity = $this->getAgentService()->getAgentByUser($user);
        }

        return match ($action) {
            /** @see PortfolioControler::portfolioAction() */
            'portfolio', => $this->computeAssertion($entity, PortfolioPrivileges::PORTFOLIO_AFFICHER),
            /** @see PortfolioControler::afficherAction() */
            'afficher' => $this->computeAssertion($entity, PortfolioPrivileges::PORTFOLIO_AFFICHER_DOCUMENT),
            /** @see PortfolioControler::historiserAction() */
            'historiser' => $this->computeAssertion($entity, PortfolioPrivileges::PORTFOLIO_HISTORISER_DOCUMENT),
            /** @see PortfolioControler::restaurerAction() */
            'restaurer' => $this->computeAssertion($entity, PortfolioPrivileges::PORTFOLIO_RESTAURER_DOCUMENT),
            /** @see PortfolioControler::supprimerAction() */
            'supprimer' => $this->computeAssertion($entity, PortfolioPrivileges::PORTFOLIO_SUPPRIMERAgentAssertionFactory.php_DOCUMENT),
            default => true,
        };
    }
}
