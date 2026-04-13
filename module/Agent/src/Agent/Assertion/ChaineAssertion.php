<?php

namespace Agent\Assertion;

use Agent\Entity\Db\Agent;
use Agent\Entity\Db\AgentAutorite;
use Agent\Entity\Db\AgentSuperieur;
use Agent\Provider\Privilege\ChainePrivileges;
use Agent\Provider\Role\RoleProvider as AgentRoleProvider;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
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
        if ($role->getRoleId() === AgentRoleProvider::ROLE_SUPERIEURE) $isSuperieur = $this->getAgentSuperieurService()->isSuperieur($agentEntity,$agent);
        if ($role->getRoleId() === AgentRoleProvider::ROLE_AUTORITE) $isAutorite = $this->getAgentAutoriteService()->isAutorite($agentEntity,$agent);
        if ($role->getRoleId() === StructureRoleProvider::OBSERVATEUR) $isObservateur = $this->getObservateurService()->isObservateur($structures, $user);

        switch ($privilege) {
            case ChainePrivileges::CHAINE_AFFICHER :
            case ChainePrivileges::CHAINE_AFFICHER_HISTORIQUE :
            case ChainePrivileges::CHAINE_SYNCHRONISER :
            case ChainePrivileges::CHAINE_GERER :
                $temp =  match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::OBSERVATEUR => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    AgentRoleProvider::ROLE_SUPERIEURE => $isSuperieur,
                    AgentRoleProvider::ROLE_AUTORITE => $isAutorite,
                    StructureRoleProvider::OBSERVATEUR, EntretienRoleProvider::OBSERVATEUR => $isObservateur,
                    AgentRoleProvider::ROLE_AGENT => $agentEntity === $agent,
                    default => false,
                };
                return $temp;
        }
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (! $entity instanceof AgentSuperieur && ! $entity instanceof AgentAutorite) {
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
            'visualiser'       => $this->computeAssertion($chaine, ChainePrivileges::CHAINE_AFFICHER_HISTORIQUE),
            'historiser', 'restaurer' => $this->computeAssertion($chaine, ChainePrivileges::CHAINE_SYNCHRONISER),
            'ajouter', 'modifier', 'supprimer' => $this->computeAssertion($chaine, ChainePrivileges::CHAINE_GERER),
            default => true,
        };
    }
}
