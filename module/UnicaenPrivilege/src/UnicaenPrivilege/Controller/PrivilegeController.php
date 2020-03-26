<?php

namespace UnicaenPrivilege\Controller;

use UnicaenPrivilege\Entity\Db\Privilege;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PrivilegeController extends AbstractActionController {
    use RoleServiceAwareTrait;
    use PrivilegeServiceAwareTrait;

    public function indexAction()
    {
        $roles = $this->getRoleService()->getRoles();
        //$roles = $this->getServiceRole()->getRepo()->findAll();
        $privileges = $this->getPrivilegeService()->getPrivilegesWithCategories();
        return new ViewModel([
            'roles' => $roles,
            'privileges' => $privileges,
        ]);
    }

    public function modifierAction()
    {
        $privilegeId = $this->params()->fromRoute("privilege");
        $roleId = $this->params()->fromRoute("role");
        /**
         * @var Privilege $privilege
         * @var Role $role
         */

        $privilege = $this->getPrivilegeService()->getPrivilege($privilegeId);
        $role = $this->getPrivilegeService()->getRole($roleId);

        $value = $this->getPrivilegeService()->toggle($role,$privilege);

        return new JsonModel([
            'value' => $value,
        ]);
        //return $this->redirect()->toRoute("roles", [], ["query" => $queryParams], true);
    }


}