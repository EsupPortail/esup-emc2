<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Validation\ValidationDemandeServiceAwareTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
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


        if ($this->getUserService()->getServiceUserContext()->getLdapUser()) {
            $identity = $this->getUserService()->getConnectedUser();
            $role = $this->getUserService()->getConnectedRole();
            $supannId = ((int) $this->getServiceUserContext()->getLdapUser()->getSupannEmpId());
            $agent = $this->getAgentService()->getAgentBySupannId($supannId);

            if ($identity !== null && $agent !== null && $agent->getUtilisateur() === null) {
                $agent->setUtilisateur($identity);
                $this->getAgentService()->update($agent);
                $personnel = $this->getRoleService()->getRoleByCode(RoleConstant::PERSONNEL);
                $hasAgent = $identity->hasRole($personnel);
                if (! $hasAgent) $this->getUserService()->addRole($identity, $personnel);
                return $this->redirect()->toRoute('agent/afficher', ['id' => $agent->getId()], [], true);
            }
        }

        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();
        $connectedUser = $this->getUserService()->getConnectedUser();


        if ($connectedRole) {
            switch ($connectedRole->getRoleId()) {
                case RoleConstant::PERSONNEL :
//                    $supannId = ((int) $this->getServiceUserContext()->getLdapUser()->getSupannEmpId());
                    $agent = $this->getAgentService()->getAgentByUser($connectedUser);
                    return $this->redirect()->toRoute('agent/afficher', ['id' => $agent->getId()], [], true);
                    break;
                case RoleConstant::VALIDATEUR :
                    return $this->redirect()->toRoute('index-validateur', [], [], true);
                    break;
                default :
                    //var_dump($connectedRole);
                    break;
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
