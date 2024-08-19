<?php

namespace Formation\Assertion;


use Formation\Entity\Db\Session;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class SessionAssertion extends AbstractAssertion {
    use SessionServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use UserServiceAwareTrait;

    public function isScopeCompatible(?Session $session, UserInterface $user, ?RoleInterface $role): bool
    {
        if ($role->getRoleId() === FormationRoles::FORMATEUR) {
            foreach ($session->getFormateurs() as $formateur) {
                if  ($formateur->getUtilisateur() === $user) return true;
            }
            return false;
        }
        return true;
    }

    private function computeAssertion(?Session $entity, string $privilege): bool
    {

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $this->getPrivilegeService()->checkPrivilege($privilege, $role);


        switch ($privilege) {
            case FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER:
                return  $this->isScopeCompatible($entity, $user, $role);
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        /** @var Session|null $entity */
        if (!$entity instanceof Session) {
            return false;
        }

        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var Session|null $entity */
        $sessionId = (($this->getMvcEvent()->getRouteMatch()->getParam('session')));
        $entity = $this->getSessionService()->getSession($sessionId);

        switch ($action) {
            case 'afficher' :
                return $this->computeAssertion($entity, FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER);
        }

        return true;
    }
}