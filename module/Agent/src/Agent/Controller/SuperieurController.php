<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class SuperieurController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
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
            if ($role->getRoleId() !== Agent::ROLE_SUPERIEURE) {
                return new ViewModel([
                    'erreur' => "Aucun·e [" . Agent::ROLE_SUPERIEURE . "] fourni·e à la route et/ou vous n'incarnez pas le rôle de [" . Agent::ROLE_SUPERIEURE . "]",
                ]);
            }
        }

        // récupération des chaînes hiérarchiques
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent);
        if (empty($superieurs)) {
            return new ViewModel([
                'erreur' => "Vous avez aucun·e agent·e sous votre responsabilité."
            ]);
        }

        // récupération des agents et affectations de ceux-ci
        $agents = array_map(function (AgentSuperieur $superieur) {
            return $superieur->getAgent();
        }, $superieurs);
        $affectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgents($agents);

        // récupération des campagnes à afficher
        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });

        //todo améliorer ce test !!!
        $userInSuperieur = $user === current($superieurs)->getSuperieur()->getUtilisateur();

        return new ViewModel([
            'userInSuperieur' => $userInSuperieur,
            'agent' => $agent,
            'agents' => $agents,
            'affectations' => $affectations,
            'campagnes' => $campagnes,
        ]);
    }
}