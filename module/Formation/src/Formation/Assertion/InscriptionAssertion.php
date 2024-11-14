<?php

namespace Formation\Assertion;


use Application\Entity\Db\Agent;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Formation\Entity\Db\Inscription;
use Formation\Provider\Privilege\InscriptionPrivileges;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class InscriptionAssertion extends AbstractAssertion
{
    use PrivilegeServiceAwareTrait;
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    public function isScopeCompatible(?Inscription $inscription, UserInterface $user, ?RoleInterface $role): bool
    {
        $inscrit = $inscription->getAgent();
        $agent = $this->getAgentService()->getAgentByLogin($user->getUsername());

        switch ($role->getRoleId()) {
            case Agent::ROLE_AGENT :
                return $inscription->getIndividu()->getUtilisateur() === $user;
            case Agent::ROLE_SUPERIEURE :
                return $this->getAgentSuperieurService()->isSuperieur($inscrit, $agent);
            case Agent::ROLE_AUTORITE :
                return $this->getAgentAutoriteService()->isAutorite($inscrit, $agent);
            case RoleProvider::RESPONSABLE:
                if ($inscrit instanceof Agent) return $this->getStructureService()->isResponsableS($inscrit->getStructures(), $agent);
                return false;
            case RoleProvider::GESTIONNAIRE:
                if ($inscrit instanceof Agent) return $this->getStructureService()->isGestionnaireS($inscrit->getStructures(), $agent);
                return false;
            case RoleProvider::OBSERVATEUR:
                if ($inscrit instanceof Agent) return $this->getObservateurService()->isObservateur($inscrit->getStructures(), $user);
                return false;
            default :
                return true;
        }
    }

    private function computeAssertion(?Inscription $entity, string $privilege): bool
    {

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        if (!$this->getPrivilegeService()->checkPrivilege($privilege, $role)) return false;


        switch ($privilege) {
            case InscriptionPrivileges::INSCRIPTION_AFFICHER:
                return $this->isScopeCompatible($entity, $user, $role);
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        /** @var Inscription|null $entity */
        if (!$entity instanceof Inscription) {
            return false;
        }

        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var Inscription|null $entity */
        $sessionId = (($this->getMvcEvent()->getRouteMatch()->getParam('inscription')));
        $entity = $this->getInscriptionService()->getInscription($sessionId);

        switch ($action) {
            case 'afficher' :
                return $this->computeAssertion($entity, InscriptionPrivileges::INSCRIPTION_AFFICHER);
            case 'afficher-agent' :
                return $this->computeAssertion($entity, AgentPrivileges::AGENT_AFFICHER);

        }

        return true;
    }
}