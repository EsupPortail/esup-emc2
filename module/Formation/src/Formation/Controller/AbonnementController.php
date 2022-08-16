<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenApp\Exception\RuntimeException;

/** @method FlashMessenger flashMessenger() */

class AbonnementController extends AbstractActionController {
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FormationServiceAwareTrait;

    public function ajouterAction() : Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent fourni");
        if ($formation === null) throw new RuntimeException("Aucune formation fournie");

        $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
        if ($abonnement) {
            $this->flashMessenger()->addWarningMessage("Vous êtes déjà abonné·e à la formation <strong>".$formation->getLibelle()."</strong>");
        } else {
            $this->getAbonnementService()->ajouterAbonnement($agent,$formation);
            $this->flashMessenger()->addSuccessMessage("Vous êtes maintenant abonné·e à la formation <strong>".$formation->getLibelle()."</strong>");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('plan-formation');
    }

    public function retirerAction() : Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent fourni");
        if ($formation === null) throw new RuntimeException("Aucune formation fournie");

        $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
        if ($abonnement) {
            $this->getAbonnementService()->retirerAbonnement($agent,$formation);
            $this->flashMessenger()->addSuccessMessage("Vous êtes maintenant désabonné·e à la formation <strong>".$formation->getLibelle()."</strong>");
        } else {
            $this->flashMessenger()->addWarningMessage("Vous n'êtes pas abonné·e à la formation <strong>".$formation->getLibelle()."</strong>");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('plan-formation');
    }

    public function listerAbonnementsParAgentAction() : ViewModel
    {
        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent fourni");

        $abonnements = $this->getAbonnementService()->getAbonnementsByAgent($agent);

        return new ViewModel([
           'title' => "Listing des abonnements pour l'agent [".$agent->getDenomination()."]",
           'agent' => $agent,
           'abonnements' => $abonnements,
        ]);
    }

    public function listerAbonnementsParFormationAction() : ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        if ($formation === null) throw new RuntimeException("Aucune formation fournie");

        $abonnements = $this->getAbonnementService()->getAbonnementsByFormation($formation);

        return new ViewModel([
            'title' => "Listing des abonnements pour la formation [".$formation->getLibelle()."]",
            'formation' => $formation,
            'abonnements' => $abonnements,
        ]);
    }
}