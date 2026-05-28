<?php

namespace EntretienProfessionnel\Controller;

use Agent\Service\Agent\AgentServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationFormAwareTrait;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationServiceAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;


class AgentForceSansObligationController extends AbstractActionController
{
    use AgentForceSansObligationServiceAwareTrait;
    use AgentForceSansObligationFormAwareTrait;
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use StructureServiceAwareTrait;

    /** @method FlashMessenger flashMessenger() */

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

        $structureId = $this->params()->fromQuery('structure');
        if ($structureId) {
            if ($structureId === -1) {
                $agentsForcesSansObligation = array_filter($agentsForcesSansObligation, function (AgentForceSansObligation $a)  {
                    return $a->getStructure() === null;
                });
            } else {
                $structure = $this->getStructureService()->getStructure($structureId);
                $agentsForcesSansObligation = array_filter($agentsForcesSansObligation, function (AgentForceSansObligation $a) use ($structure) {
                    return $a->getStructure() === $structure;
                });
            }
        } else $structure = null;

        return new ViewModel([
            'agentsForcesSansObligation' => $agentsForcesSansObligation,
            'campagnes' => $campagnes,

            'agent' => $agent,
            'campagne' => $campagne,
            'forcage' => $forcage,
            'structure' => $structureId,
            'structuresGroups' =>  $this->getStructureService()->getStructuresAsOptionGroup(),
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        $agent = $agentForceSansObligation->getAgent();
        $campagne = $agentForceSansObligation->getCampagne();
        $affectations = $agent->getAffectations($campagne->getDateDebut());
        return new ViewModel([
            'title' => "Exception à un entretien professionnel",
            'agentForceSansObligation' => $agentForceSansObligation,
            'agent' => $agent,
            'campagne' => $campagne,
            'affectations' => $affectations,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $agentForceSansObligation = new AgentForceSansObligation();
        $form = $this->getAgentForceSansObligationForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/agent-avec-forcage/ajouter', [], [], true));
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
            'title' => "Ajouter une exception d'entretien professionnel",
            'form' => $form
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        $form = $this->getAgentForceSansObligationForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/agent-avec-forcage/modifier', ['agent-force-sans-obligation' => $agentForceSansObligation->getId()], [], true));
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
            'title' => "Modifier l'exception d'entretien professionnel",
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
        return $this->redirect()->toRoute('entretien-professionnel/agent-avec-forcage', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $agentForceSansObligation = $this->getAgentForceSansObligationService()->getRequestedAgentForceSansObligation($this);
        $this->getAgentForceSansObligationService()->restore($agentForceSansObligation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/agent-avec-forcage', [], [], true);
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
                'title' => "Suppression de l'exception pour [" . $agentForceSansObligation->getAgent()->getDenomination() . "] et la campagne [" . $agentForceSansObligation->getCampagne()->getAnnee() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/agent-avec-forcage/supprimer', ["agent-force-sans-obligation" => $agentForceSansObligation->getId()], [], true),
            ]);
        }
        return $vm;
    }
}