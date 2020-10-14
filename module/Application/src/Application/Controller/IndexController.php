<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Application\Service\Validation\ValidationDemandeServiceAwareTrait;
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

    use ValidationDemandeServiceAwareTrait;

    public function indexAction()
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        /** @var Agent $agent */
        $agent = null;

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
                    break;
                case RoleConstant::GESTIONNAIRE :
                    $structures = $this->getStructureService()->getStructuresByGestionnaire($connectedUser);
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/afficher', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case RoleConstant::RESPONSABLE :
                    $structures = $this->getStructureService()->getStructuresByResponsable($connectedUser);
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/afficher', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case RoleConstant::VALIDATEUR :
                    return $this->redirect()->toRoute('index-validateur', [], [], true);
                    break;
            }
        }

        return new ViewModel([
            'user' => $connectedUser,
            'role' => $connectedRole,
            'agent' => $agent,
        ]);
    }

    public function indexValidateurAction()
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        $demandes = $this->getValidationDemandeService()->getValidationsDemandesByValidateur($user);

        return new ViewModel([
            'em' => $this->getValidationDemandeService()->getEntityManager(),
            'user' => $user,
            'demandes' => $demandes,
        ]);
    }

    public function indexRessourcesAction()
    {
        return new ViewModel();
    }

    public function indexGestionAction()
    {
        return new ViewModel();
    }

    public function indexAdministrationAction()
    {
        return new ViewModel();
    }

}
