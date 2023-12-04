<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMobilite;
use Application\Form\AgentMobilite\AgentMobiliteFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentMobilite\AgentMobiliteServiceAwareTrait;
use Carriere\Service\Mobilite\MobiliteServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;

class AgentMobiliteController extends AgentController {
    use AgentServiceAwareTrait;
    use AgentMobiliteServiceAwareTrait;
    use MobiliteServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AgentMobiliteFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $mobilites = $this->getAgentMobiliteService()->getAgentsMobilites();
        $types = $this->getMobiliteService()->getMobilites();

        $agent = null;
        if ($agentArray = $this->params()->fromQuery('agent')) {
            $agent = $this->getAgentService()->getAgent($agentArray['id']);
        }
        $structure = null;
        if ($structureArray = $this->params()->fromQuery('structure')) {
            $structure = $this->getStructureService()->getStructure($structureArray['id']);
        }
        $mobilite = null;
        if ($mobiliteId = $this->params()->fromQuery('mobilite')) {
            $mobilite = $this->getMobiliteService()->getMobilite((int) $mobiliteId);
        }

        if ($agent) $mobilites = array_filter($mobilites, function (AgentMobilite $a) use ($agent) { return $a->getAgent() === $agent;});
        if ($structure) $mobilites = array_filter($mobilites, function (AgentMobilite $a) use ($structure) { return !empty($a->getAgent()->getAffectationsActifs(null, [$structure])); });
        if ($mobilite) $mobilites = array_filter($mobilites, function (AgentMobilite $a) use ($mobilite) { return $a->getMobilite() === $mobilite;});

        return new ViewModel([
            'mobilites' => $mobilites,
            'types' => $types,
            'agent' => $agent,
            'structure' => $structure,
            'mobilite' => $mobilite,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $agentMobilite = new AgentMobilite();
        $agentMobilite->setAgent($agent);

        $form = $this->getAgentMobiliteForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/mobilite/ajouter', ['agent' => ($agent)?$agent->getId():null], [], true));
        $form->bind($agentMobilite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                //historiser les éventuelles ouvertes
                $agent = $agentMobilite->getAgent();
                $mobilites = $this->getAgentMobiliteService()->getAgentsMobilitesByAgent($agent);
                foreach ($mobilites as $mobilite) $this->getAgentMobiliteService()->historise($mobilite);
                // enregistrer la nouvelle
                $this->getAgentMobiliteService()->create($agentMobilite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une mobilité",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;

    }

    public function modifierAction() : ViewModel
    {
        $agentMobilite = $this->getAgentMobiliteService()->getRequestedAgentMobilite($this);

        $form = $this->getAgentMobiliteForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/mobilite/modifier', ['agent-mobilite' => $agentMobilite->getId()], [], true));
        $form->bind($agentMobilite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getAgentMobiliteService()->update($agentMobilite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'une mobilité",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : ViewModel
    {
        $agentMobilite = $this->getAgentMobiliteService()->getRequestedAgentMobilite($this);
        $this->getAgentMobiliteService()->historise($agentMobilite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentMobiliteService()->historise($agentMobilite);
            exit();
        }

        $vm = new ViewModel();
        if ($agentMobilite !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Historisation du statut de mobilité pour l'agent·e " . $agentMobilite->getAgent()->getDenomination(),
                'text' => "L'historisation supprimera votre statut. <br/>Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/mobilite/historiser', ["agent-mobilite" => $agentMobilite->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function restaurerAction() : Response
    {
        $agentMobilite = $this->getAgentMobiliteService()->getRequestedAgentMobilite($this);
        $this->getAgentMobiliteService()->restore($agentMobilite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentMobilite->getAgent()->getId()], ['fragment' => "mobilite"], true);
    }

    public function supprimerAction() : ViewModel
    {
        $agentMobilite = $this->getAgentMobiliteService()->getRequestedAgentMobilite($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentMobiliteService()->delete($agentMobilite);
            exit();
        }

        $vm = new ViewModel();
        if ($agentMobilite !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une mobilité pour l'agent " . $agentMobilite->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/mobilite/supprimer', ["agent-mobilite" => $agentMobilite->getId()], [], true),
            ]);
        }
        return $vm;
    }
}