<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Service\Role\RoleServiceAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;
    use UserContextServiceAwareTrait;

    public function indexAction()
    {
        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole) {
            switch ($connectedRole->getRoleId()) {
                case Role::PERSONNEL :
                    $this->redirect()->toRoute('index-personnel', [], [], true);
                    break;
                default :
                    var_dump($connectedRole);
                    break;
            }
        }

        $identity = $this->getUserService()->getConnectedUser();

        // !TODO bouger cela pour faire plus propre ...
        $agent = $this->getAgentService()->getAgentByUser($identity);
        if ($identity !== null && $agent === null) {
            $people = $this->getServiceUserContext()->getLdapUser();
            if ($people->getSupannEmpId() !== null) {
                $personnel = $this->getRoleService()->getRoleByCode(Role::PERSONNEL);
                $this->getAgentService()->createFromLDAP($people, $identity);
                $this->getUserService()->addRole($identity, $personnel);
                $this->redirect()->toRoute('home', [], [], true);
            }
        }



        return new ViewModel([
            'user' => $identity,
        ]);
    }

    public function indexPersonnelAction() {

        $user = $this->getUserService()->getConnectedUser();

        return new ViewModel([
           'user' => $user,
        ]);
    }

    public function administrationAction() {
        return new ViewModel();
    }
}
