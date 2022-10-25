<?php

namespace Application\Controller;


use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use EntretienProfessionnel\Provider\Role\EntretienProfessionnelRoles;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use UserContextServiceAwareTrait;

    use FichePosteServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;

    public function indexAction()
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        if ($connectedUser === null) return new ViewModel([]);

        $agent = $this->getAgentService()->getAgentByUser($connectedUser);
        if ($agent !== null && $agent->getUtilisateur() === null) {
            $agent->setUtilisateur($connectedUser);
            $this->getAgentService()->update($agent);
//            return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
        }

        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole) {
            switch ($connectedRole->getRoleId()) {
                case AppRoleProvider::AGENT :
                    $agent = $this->getAgentService()->getAgentByUser($connectedUser);
                    return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
//                case RoleConstant::VALIDATEUR :
//                    return $this->redirect()->toRoute('index-validateur', [], [], true);
                case RoleProvider::GESTIONNAIRE :
                    $structures = $this->getStructureService()->getStructuresByGestionnaire($connectedUser);
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/afficher', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case RoleProvider::RESPONSABLE :
                    $structures = $this->getStructureService()->getStructuresByResponsable($connectedUser);
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/afficher', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case EntretienProfessionnelRoles::ROLE_DELEGUE :
                    return $this->redirect()->toRoute('entretien-professionnel/index-delegue', [], [], true);
                case Agent::ROLE_SUPERIEURE :
                    /** @see IndexController::indexSuperieurAction() */
                    return $this->redirect()->toRoute('index-superieur', [], [], true);
                case Agent::ROLE_AUTORITE :
                    /** @see IndexController::indexAutoriteAction() */
                    return $this->redirect()->toRoute('index-autorite', [], [], true);
            }
        }

        return new ViewModel([
            'user' => $connectedUser,
            'role' => $connectedRole,
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

    public function indexSuperieurAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $complements = $this->getAgentService()->getSuperieurByUser($user);

        $agents = [];
        if ($complements) {
            foreach ($complements as $complement) {
                $agent = $this->getAgentService()->getAgent($complement->getAttachmentId());
                if ($agent !== null) $agents[$agent->getId()] = $agent;
            }
        }
        $fiches = [];
        $fichesRAW = $this->getFichePosteService()->getFichesPostesbyAgents($agents);
        foreach ($fichesRAW as $fiche) {
//            $fiches[$fiche->getAgent()->getId()][] = $fiche;
        }



        return new ViewModel([
            'agents' => $agents,
            'connectedAgent' => $this->getAgentService()->getAgentByUser($user),
            'campagnePrevious' => $this->getCampagneService()->getLastCampagne(),
            'campagnesCurrents' => $this->getCampagneService()->getCampagnesActives(),

            'fiches' => $fiches,
            'demandesInternes' => $this->getFormationInstanceInscritService()->getInscrpitionsByAgentsAndAnnee($agents),
            'demandesExternes' => $this->getDemandeExterneService()->getDemandesExternesByAgentsAndAnnee($agents),
        ]);
    }

    public function indexAutoriteAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $complements = $this->getAgentService()->getAutoriteByUser($user);

        $agents = [];
        if ($complements) {
            foreach ($complements as $complement) {
                $agent = $this->getAgentService()->getAgent($complement->getAttachmentId());
                if ($agent !== null) $agents[$agent->getId()] = $agent;
            }
        }
        usort($agents, function (Agent $a, Agent $b) {
            $aaa = $a->getNomUsuel() . " "  . $a->getPrenom();
            $bbb = $b->getNomUsuel() . " "  . $b->getPrenom();
            return $aaa > $bbb;
        });

        $vm =  new ViewModel();
        $vm->setVariables([
            'agents' => $agents,
            'connectedAgent' => $this->getAgentService()->getAgentByUser($user),
            'campagnePrevious' => $this->getCampagneService()->getLastCampagne(),
            'campagnesCurrents' => $this->getCampagneService()->getCampagnesActives(),
        ]);
        return $vm;
    }

    public function infosAction() : ViewModel
    {
        return new ViewModel();
    }
}
