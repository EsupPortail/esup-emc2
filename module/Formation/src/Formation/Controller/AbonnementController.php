<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\FormationAbonnement;
use Formation\Form\Abonnement\AbonnementFormAwareTrait;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenApp\Exception\RuntimeException;

/** @method FlashMessenger flashMessenger() */
class AbonnementController extends AbstractActionController
{
    use AbonnementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FormationServiceAwareTrait;
    use AbonnementFormAwareTrait;

    public function ajouterAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        if ($formation === null) {

            $abonnement = new FormationAbonnement();
            $form = $this->getAbonnementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/abonnement/ajouter', [], [], true));
            $form->bind($abonnement);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getAbonnementService()->create($abonnement);
                    $this->flashMessenger()->addSuccessMessage("<strong>" . $abonnement->getAgent()->getDenomination() . "</strong> est maintenant abonné·e à la formation <strong>" . $abonnement->getFormation()->getLibelle() . "</strong>");
                    exit();
                }
            }

            $vm = new ViewModel([
                'title' => "Ajouter un abonnement à un agent",
                'form' => $form,
            ]);
            $vm->setTemplate('formation/default/default-form');
            return $vm;
        }

        $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent fourni");

        $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
        if ($abonnement) {
            $this->flashMessenger()->addWarningMessage("Vous êtes déjà abonné·e à la formation <strong>" . $formation->getLibelle() . "</strong>");
        } else {
            $this->getAbonnementService()->ajouterAbonnement($agent, $formation);
            $this->flashMessenger()->addSuccessMessage("Vous êtes maintenant abonné·e à la formation <strong>" . $formation->getLibelle() . "</strong>");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('plan-de-formation/courant');
    }

    public function retirerAction(): Response
    {
        $abonnement = $this->getAbonnementService()->getRequestedAbonnement($this);

        if ($abonnement !== null) {
            $formation = $abonnement->getFormation();
            $agent = $abonnement->getAgent();
            $this->getAbonnementService()->retirerAbonnement($agent, $formation);
            $this->flashMessenger()->addSuccessMessage("Vous êtes maintenant désabonné·e à la formation <strong>" . $formation->getLibelle() . "</strong>");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('plan-de-formation/courant');
    }

    public function listerAbonnementsParAgentAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($agent === null) $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent fourni");

        $abonnements = $this->getAbonnementService()->getAbonnementsByAgent($agent);

        $vm = new ViewModel([
            'title' => "Listing des abonnements pour l'agent [" . $agent->getDenomination() . "]",
            'agent' => $agent,
            'abonnements' => $abonnements,
        ]);
        $vm->setTemplate('formation/abonnement/lister-abonnements');
        return $vm;
    }

    public function listerAbonnementsParFormationAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        if ($formation === null) throw new RuntimeException("Aucune formation fournie");

        $abonnements = $this->getAbonnementService()->getAbonnementsByFormation($formation);

        $vm = new ViewModel([
            'title' => "Listing des abonnements pour la formation [" . $formation->getLibelle() . "]",
            'formation' => $formation,
            'abonnements' => $abonnements,
        ]);
        $vm->setTemplate('formation/abonnement/lister-abonnements');
        return $vm;
    }
}