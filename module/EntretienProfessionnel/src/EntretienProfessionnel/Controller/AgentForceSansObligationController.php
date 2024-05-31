<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationFormAwareTrait;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationServiceAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class AgentForceSansObligationController extends AbstractActionController
{
    use AgentForceSansObligationServiceAwareTrait;
    use AgentForceSansObligationFormAwareTrait;
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $agentsForcesSansObligation = $this->getAgentForceSansObligationService()->getAgentsForcesSansObligation('id', 'ASC', true);
        $campagnes = $this->getCampagneService()->getCampagnes();

        $campagne = $this->getCampagneService()->getCampagne((int) $this->params()->fromQuery('campagne'));
        if ($campagne) $agentsForcesSansObligation = array_filter($agentsForcesSansObligation, function (AgentForceSansObligation $a) use ($campagne) { return $a->getCampagne() === $campagne;});
        $agent = null;
        if ($agentArray = $this->params()->fromQuery('agent')) {
            $agent = $this->getAgentService()->getAgent($agentArray);
        }
        if ($agent) $agentsForcesSansObligation = array_filter($agentsForcesSansObligation, function (AgentForceSansObligation $a) use ($agent) { return $a->getAgent() === $agent;});
        $forcage = $this->params()->fromQuery('forcage');
        if ($forcage) $agentsForcesSansObligation = array_filter($agentsForcesSansObligation, function (AgentForceSansObligation $a) use ($forcage) { return $a->getType() === $forcage;});

        return new ViewModel([
            'agentsForcesSansObligation' => $agentsForcesSansObligation,
            'campagnes' => $campagnes,

            'agent' => $agent,
            'campagne' => $campagne,
            'forcage' => $forcage,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        return new ViewModel([
            'agentForceSansObligation' => $agentForceSansObligation,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $agentForceSansObligation = new AgentForceSansObligation();
        $form = $this->getAgentForceSansObligationForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/agent-force-sans-obligation/ajouter', [], [], true));
        $form->bind($agentForceSansObligation);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentForceSansObligationService()->create($agentForceSansObligation);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter un·e agent·e sans obligation d'entretien professionnel",
            'form' => $form
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        $form = $this->getAgentForceSansObligationForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/agent-force-sans-obligation/modifier', ['agent-force-sans-obligation' => $agentForceSansObligation->getId()], [], true));
        $form->bind($agentForceSansObligation);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentForceSansObligationService()->update($agentForceSansObligation);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier un·e agent·e sans obligation d'entretien professionnel",
            'form' => $form
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        $this->getAgentForceSansObligationService()->historise($agentForceSansObligation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/agent-force-sans-obligation', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        $this->getAgentForceSansObligationService()->restore($agentForceSansObligation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/agent-force-sans-obligation', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentForceSansObligationService()->delete($agentForceSansObligation);
            exit();
        }

        $vm = new ViewModel();
        if ($agentForceSansObligation !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du forçage pour [" . $agentForceSansObligation->getAgent()->getDenomination() . "] et la campagne [" . $agentForceSansObligation->getCampagne()->getAnnee() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/agent-force-sans-obligation/supprimer', ["agent-force-sans-obligation" => $agentForceSansObligation->getId()], [], true),
            ]);
        }
        return $vm;
    }
}