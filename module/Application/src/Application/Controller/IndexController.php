<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnelConstant;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use UserContextServiceAwareTrait;


    public function indexAction()
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();

        if ($this->getServiceUserContext()->getLdapUser()) {
            $supannId = ((int) $this->getServiceUserContext()->getLdapUser()->getSupannEmpId());
            $agent = $this->getAgentService()->getAgentBySupannId($supannId);

            if ($connectedUser !== null && $agent !== null && $agent->getUtilisateur() === null) {
                $agent->setUtilisateur($connectedUser);
                $this->getAgentService()->update($agent);
                $personnel = $this->getRoleService()->getRoleByCode(RoleConstant::PERSONNEL);
                $hasAgent = $connectedUser->hasRole($personnel);
                if (! $hasAgent) $this->getUserService()->addRole($connectedUser, $personnel);
                return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
            }
        }

        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole) {
            switch ($connectedRole->getRoleId()) {
                case RoleConstant::PERSONNEL :
                    $agent = $this->getAgentService()->getAgentByUser($connectedUser);
                    return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
                case RoleConstant::VALIDATEUR :
                    return $this->redirect()->toRoute('index-validateur', [], [], true);
                case RoleConstant::GESTIONNAIRE :
                    $structures = $this->getStructureService()->getStructuresByGestionnaire($connectedUser);
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/afficher', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case RoleConstant::RESPONSABLE :
                    $structures = $this->getStructureService()->getStructuresByResponsable($connectedUser);
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/afficher', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case EntretienProfessionnelConstant::ROLE_DELEGUE :
                    return $this->redirect()->toRoute('entretien-professionnel/index-delegue', [], [], true);
                    break;
            }
        }


        $tous_mes_roles = $connectedUser->getRoles();

        $roles = [];
        foreach ($tous_mes_roles as $role_actuel) {
            if ($role_actuel !== $connectedRole) {
                $roles[] = $role_actuel;
            }
        }

        return new ViewModel([
            'user' => $connectedUser,
            'role' => $connectedRole,
            'roles' => $roles,
        ]);
    }

    public function indexRessourcesAction() : ViewModel
    {
        return new ViewModel();
    }

    public function indexGestionAction() : ViewModel
    {
        return new ViewModel();
    }

    public function indexAdministrationAction() : ViewModel
    {
        return new ViewModel();
    }

}
