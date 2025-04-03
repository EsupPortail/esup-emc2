<?php

namespace Application\Assertion;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Provider\Privilege\ChainePrivileges;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use EntretienProfessionnel\Provider\Role\RoleProvider as EntretienRoleProvider;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class ChaineAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use UserServiceAwareTrait;

    public function computeAssertion($entity, string $privilege) : bool
    {
        if ($entity && !$entity instanceof AgentSuperieur && !$entity instanceof AgentAutorite) {
            return false;
        }

        /** @var Agent $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $agentEntity = ($entity)?->getAgent();
        $structures = ($agentEntity)?->getStructures();

        $isResponsable = false;
        $isSuperieur = false;
        $isAutorite = false;
        $isObservateur = false;
        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE) $isResponsable = $this->getStructureService()->isResponsableS($structures, $agent);
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($agentEntity,$agent);
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($agentEntity,$agent);
        if ($role->getRoleId() === StructureRoleProvider::OBSERVATEUR) $isObservateur = $this->getObservateurService()->isObservateur($structures, $user);

        switch ($privilege) {
            case ChainePrivileges::CHAINE_AFFICHER :
            case ChainePrivileges::CHAINE_SYNCHRONISER :
            case ChainePrivileges::CHAINE_GERER :
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::OBSERVATEUR => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    Agent::ROLE_SUPERIEURE => $isSuperieur,
                    Agent::ROLE_AUTORITE => $isAutorite,
                    StructureRoleProvider::OBSERVATEUR, EntretienRoleProvider::OBSERVATEUR => $isObservateur,
                    AppRoleProvider::AGENT => $agentEntity === $agent,
                    default => false,
                };
        }
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (! $entity instanceof Agent) {
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
        $chaineId = (($this->getMvcEvent()->getRouteMatch()->getParam('chaine')));
        $type = (($this->getMvcEvent()->getRouteMatch()->getParam('type')));
        $agentId = (($this->getMvcEvent()->getRouteMatch()->getParam('agent')));

        $agent = $this->getAgentService()->getAgent($agentId);
        $chaine = null;
        if ($chaineId AND $type) {
            if ($type === 'superieur') $chaine = $this->getAgentSuperieurService()->getAgentSuperieur($chaineId);
            if ($type === 'autorite') $chaine = $this->getAgentAutoriteService()->getAgentAutorite($chaineId);
        }
        if ($agent !== null AND $chaine === null) {
            $chaine = new AgentAutorite(); $chaine->setAgent($agent);
        }

        return match ($action) {
            'afficher'       => $this->computeAssertion($chaine, ChainePrivileges::CHAINE_AFFICHER),
            'historiser', 'restaurer' => $this->computeAssertion($chaine, ChainePrivileges::CHAINE_SYNCHRONISER),
            'ajouter', 'modifier', 'supprimer' => $this->computeAssertion($chaine, ChainePrivileges::CHAINE_GERER),
            default => true,
        };
    }
}
