<?php


namespace Structure\Assertion;

use Agent\Entity\Db\Agent;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use EntretienProfessionnel\Provider\Role\RoleProvider as EntretienRoleProvider;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Controller\StructureController;
use Structure\Controller\StructureResponsabiliteController;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Provider\Privilege\ResponsabilitePrivileges;
use Structure\Provider\Role\RoleProvider;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Observateur\ObservateurStructureServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureGestionnaire\StructureGestionnaireServiceAwareTrait;
use Structure\Service\StructureResponsable\StructureResponsableServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ResponsabiliteAssertion extends AbstractAssertion {

    use AgentServiceAwareTrait;
    use ObservateurStructureServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureGestionnaireServiceAwareTrait;
    use StructureResponsableServiceAwareTrait;
    use UserServiceAwareTrait;
    public function computeAssertion($entity, string $privilege) : bool
    {
        if ($entity && !$entity instanceof StructureGestionnaire && !$entity instanceof StructureResponsable) {
            return false;
        }

        /** @var Agent $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $structure = $entity?->getStructure();

        $isResponsable = false;
        $isGestionnaire = false;
        $isObservateur = false;
        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE) $isResponsable = $this->getStructureService()->isResponsable($structure, $agent);
        if ($role->getRoleId() === StructureRoleProvider::GESTIONNAIRE) $isGestionnaire = $this->getStructureService()->isGestionnaire($structure,$agent);
        if ($role->getRoleId() === StructureRoleProvider::OBSERVATEUR) $isObservateur = $this->getObservateurStructureService()->isObservateur([$structure], $user);

        switch ($privilege) {
            case ResponsabilitePrivileges::RESPONSABILITE_AFFICHER :
            case ResponsabilitePrivileges::RESPONSABILITE_AFFICHER_HISTORIQUE :
            case ResponsabilitePrivileges::RESPONSABILITE_SYNCHRONISER :
            case ResponsabilitePrivileges::RESPONSABILITE_GERER :
                $temp =  match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::OBSERVATEUR => true,
                    StructureRoleProvider::RESPONSABLE => $isResponsable,
                    StructureRoleProvider::GESTIONNAIRE => $isGestionnaire,
                    StructureRoleProvider::OBSERVATEUR, EntretienRoleProvider::OBSERVATEUR => $isObservateur,
                    default => false,
                };
                return $temp;
        }
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        if (! $entity instanceof StructureGestionnaire && ! $entity instanceof StructureResponsable) {
            return false;
        }
        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $responsabiliteId = (($this->getMvcEvent()->getRouteMatch()->getParam('responsabilite')));
        $role = (($this->getMvcEvent()->getRouteMatch()->getParam('role')));
        $structureId = (($this->getMvcEvent()->getRouteMatch()->getParam('structure')));

        $responsabilite = null;
        if ($responsabiliteId AND $role) {
            if ($role === RoleProvider::GESTIONNAIRE) $responsabilite = $this->getStructureGestionnaireService()->getStructureGestionnaire($responsabiliteId);
            if ($role === RoleProvider::RESPONSABLE) $responsabilite = $this->getStructureResponsableService()->getStructureResponsable($responsabiliteId);
        }
        if ($structureId !== null AND $responsabilite === null) {
            /** @var Structure|null $structure */
            $structure = $this->getStructureService()->getStructure($structureId);
            $responsabilite = new StructureResponsable(); $responsabilite->setStructure($structure);
        }

        return match ($action) {
            /** @see StructureResponsabiliteController::afficherResponsabiliteAction() */
            'afficher-responsabilite'       => $this->computeAssertion($responsabilite, ResponsabilitePrivileges::RESPONSABILITE_AFFICHER),
            'historiser', 'restaurer' => $this->computeAssertion($responsabilite, ResponsabilitePrivileges::RESPONSABILITE_SYNCHRONISER),
            'ajouter', 'modifier', 'supprimer' => $this->computeAssertion($responsabilite, ResponsabilitePrivileges::RESPONSABILITE_GERER),
            default => true,
        };
    }
}