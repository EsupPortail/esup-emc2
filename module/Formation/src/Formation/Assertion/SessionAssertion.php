<?php

namespace Formation\Assertion;


use Formation\Entity\Db\FormationInstance;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class SessionAssertion extends AbstractAssertion {
    use FormationInstanceServiceAwareTrait;
    use PrivilegeCategorieServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use UserServiceAwareTrait;

    public function isScopeCompatible(?FormationInstance $session, UserInterface $user, ?RoleInterface $role): bool
    {
        if ($role->getRoleId() === FormationRoles::FORMATEUR) {
            foreach ($session->getFormateurs() as $formateur) {
                if  ($formateur->getUtilisateur() === $user) return true;
            }
            return false;
        }
        return true;
    }

    private function computeAssertion(?FormationInstance $entity, string $privilege): bool
    {

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        //todo QUESTION : pourquoi devoir faire cela c'est pas normal !!!
        //todo UPDATE : dans le projet SyGAL le classe mère 'in house' fait cela ...
        [$catCode, $priCode] = explode('-', $privilege);
        $categorie = $this->getPrivilegeCategorieService()->findByCode($catCode);
        $pprivilege = $this->getPrivilegeService()->findByCode($priCode, $categorie->getId());
        $listings = $pprivilege->getRoles()->toArray();
        if (!in_array($role, $listings)) return false;
        //todo FIN BIZARERIE ...


        switch ($privilege) {
            case FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER:
                return  $this->isScopeCompatible($entity, $user, $role);
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        /** @var FormationInstance|null $entity */
        if (!$entity instanceof FormationInstance) {
            return false;
        }

        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        /** @var FormationInstance|null $entity */
        $sessionId = (($this->getMvcEvent()->getRouteMatch()->getParam('formation-instance')));
        $entity = $this->getFormationInstanceService()->getFormationInstance($sessionId);

        switch ($action) {
            case 'afficher' :
                return $this->computeAssertion($entity, FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER);
        }

        return true;
    }
}