<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Validation\ValidationDemandeServiceAwareTrait;
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

    use ValidationDemandeServiceAwareTrait;

    public function indexAction()
    {
        $identity = null;
        $agent = null;

        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole) {
            switch ($connectedRole->getRoleId()) {
                case Role::PERSONNEL :
                    return $this->redirect()->toRoute('index-personnel', [], [], true);
                    break;
                case Role::VALIDATEUR :
                    return $this->redirect()->toRoute('index-validateur', [], [], true);
                    break;
                default :
                    //var_dump($connectedRole);
                    break;
            }
        }

        if ($this->getUserService()->getServiceUserContext()->getLdapUser()) {
            $supannId = ((int)$this->getUserService()->getServiceUserContext()->getLdapUser()->getSupannEmpId());
            $identity = $this->getUserService()->getConnectedUser();

            // !TODO bouger cela pour faire plus propre ...
            $agent = $this->getAgentService()->getAgentBySupannId($supannId);
            if ($identity !== null && $agent !== null && $agent->getUtilisateur() === null) {
                $agent->setUtilisateur($identity);
                $this->getAgentService()->update($agent);
                $personnel = $this->getRoleService()->getRoleByCode(Role::PERSONNEL);
                $this->getUserService()->addRole($identity, $personnel);
                return $this->redirect()->toRoute('home', [], [], true);
            }
        }

        return new ViewModel([
            'user' => $identity,
            'agent' => $agent,
        ]);
    }

    public function indexPersonnelAction() {

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $missions = $agent->getMissionsSpecifiques();
        usort($missions, function (AgentMissionSpecifique $a, AgentMissionSpecifique $b) { return $a->getDateDebut() > $b->getDateDebut();});

        return new ViewModel([
            'user' => $user,
            'agent' => $agent,
            'fiche' => (!empty($agent->getFiches()))?current($agent->getFiches()):null,
            'missions' => $missions,
        ]);
    }

    public function indexValidateurAction() {

        $user = $this->getUserService()->getConnectedUser();
        $demandes = $this->getValidationDemandeService()->getValidationsDemandesByValidateur($user);

        return new ViewModel([
            'em' => $this->getValidationDemandeService()->getEntityManager(),
            'user' => $user,
            'demandes' => $demandes,
        ]);
    }

    public function administrationAction() {
        return new ViewModel();
    }
}
