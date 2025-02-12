<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AutoriteController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use UserServiceAwareTrait;

    public function agentsAction(): ViewModel
    {
        // recupération de l'agent
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $user = $this->getUserService()->getConnectedUser();
        if ($agent === null) $agent = $this->getAgentService()->getAgentByUser($user);
        if ($agent === null) {
            $role = $this->getUserService()->getConnectedRole();
            if ($role->getRoleId() !== Agent::ROLE_AUTORITE) {
                return new ViewModel([
                    'erreur' => "Aucun·e [" . Agent::ROLE_AUTORITE . "] fourni·e à la route et/ou vous n'incarnez pas le rôle de [" . Agent::ROLE_AUTORITE . "]",
                ]);
            }
        }

        // récupération des chaînes hiérarchiques
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent);
        if (empty($autorites)) {
            return new ViewModel([
                'erreur' => "Vous avez aucun·e agent·e sous votre autorité."
            ]);
        }

        // récupération des agents et affectations de ceux-ci
        $agents = array_map(function (AgentAutorite $autorite) {return $autorite->getAgent();}, $autorites);
        $affectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgents($agents);

        // récupération des campagnes à afficher
        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() <=> $b->getDateDebut();});

        //todo améliorer ce test !!!
        $userInAutorite = $user === current($autorites)->getAutorite()->getUtilisateur();

        return new ViewModel([
            'userIsAutorite' => $userInAutorite,
            'agent' => $agent,
            'agents' => $agents,
            'affectations' => $affectations,
            'campagnes' => $campagnes,
        ]);
    }
}