<?php

namespace Agent\Assertion;

use Agent\Controller\AgentMobiliteController;
use Agent\Entity\Db\Agent;
use Agent\Entity\Db\AgentMobilite;
use Agent\Provider\Privilege\AgentmobilitePrivileges;
use Agent\Provider\Privilege\AgentPrivileges;
use Agent\Provider\Privilege\ChainePrivileges;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentMobilite\AgentMobiliteServiceAwareTrait;
use Application\Service\Util\UtilServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentMobiliteAssertion extends AbstractAssertion
{
    use AgentServiceAwareTrait;
    use AgentMobiliteServiceAwareTrait;
    use UserServiceAwareTrait;
    use UtilServiceAwareTrait;


    public function computeAssertion(AgentMobilite $agentMobilite, string $privilege): bool
    {
        /** @var Agent $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);

        switch ($privilege) {
            case AgentmobilitePrivileges::AGENTMOBILITE_AFFICHER :
            case AgentmobilitePrivileges::AGENTMOBILITE_AJOUTER :
            case AgentmobilitePrivileges::AGENTMOBILITE_MODIFIER :
            case AgentmobilitePrivileges::AGENTMOBILITE_HISTORISER :
            case AgentmobilitePrivileges::AGENTMOBILITE_SUPPRIMER :
                return in_array($agentMobilite->getAgent(), $agents);
        }

        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        if (!$entity instanceof AgentMobilite) {
            return false;
        }
        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $agentMobiliteId =$this->getMvcEvent()->getRouteMatch()->getParam('agent-mobilite');
        $agentMobilite = $this->getAgentMobiliteService()->getAgentMobilite($agentMobiliteId);

        //si l'élément considéré n'est pas un AgentMobilite alors tester si Agent et crée "fausse" mobilité
        if ($agentMobilite === null) {
            $agentId = $this->getMvcEvent()->getRouteMatch()->getParam('agent');
            $agent = $this->getAgentService()->getAgent($agentId);

            if ($agent !== null) {
                $agentMobilite = new AgentMobilite();
                $agentMobilite->setAgent($agent);
            }
        }

        if ($agentMobilite === null) return false;


        return match ($action) {
            /** @see AgentMobiliteController::afficherAgentAction() */
            'afficher-agent' => $this->computeAssertion($agentMobilite, AgentmobilitePrivileges::AGENTMOBILITE_AFFICHER),
            /** @see AgentMobiliteController::ajouterAction() */
            'ajouter' => $this->computeAssertion($agentMobilite, AgentmobilitePrivileges::AGENTMOBILITE_AJOUTER),
            /** @see AgentMobiliteController::modifierAction() */
            'modifier' => $this->computeAssertion($agentMobilite, AgentmobilitePrivileges::AGENTMOBILITE_MODIFIER),
            /** @see AgentMobiliteController::historiserAction() */
            /** @see AgentMobiliteController::restaurerAction() */
            'historiser', 'restaurer' => $this->computeAssertion($agentMobilite, AgentmobilitePrivileges::AGENTMOBILITE_HISTORISER),
            /** @see AgentMobiliteController::supprimerAction() */
            'supprimer' => $this->computeAssertion($agentMobilite, AgentmobilitePrivileges::AGENTMOBILITE_SUPPRIMER),
            /** @see AgentMobiliteController::indexAction() */
            default => true,
        };

    }
}
