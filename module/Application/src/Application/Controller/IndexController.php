<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Utilisateur\Entity\Db\Role;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use UserServiceAwareTrait;

    public function indexAction()
    {
        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();

        switch ($connectedRole->getRoleId()) {
            case Role::PERSONNEL :
                $this->redirect()->toRoute('index-personnel', [], [], true);
                break;
        }

        $identity = $this->getUserService()->getConnectedUser();
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
