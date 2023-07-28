<?php

namespace Application\Controller;

use Application\Entity\Db\AgentStageObservation;
use Application\Form\AgentStageObservation\AgentStageObservationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentStageObservation\AgentStageObservationServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

class AgentStageObservationController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentStageObservationServiceAwareTrait;
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;

    use AgentStageObservationFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $stageObservation = new AgentStageObservation();
        $stageObservation->setAgent($agent);

        $form = $this->getAgentStageObservationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/stageobs/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($stageObservation);

        $categorie = $this->getEtatCategorieService()->getEtatCategorieByCode('STAGE_OBSERVATION');
        $types = $this->getEtatTypeService()->getEtatsTypesByCategorie($categorie);
        $form->get('etat')->resetEtats($types);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentStageObservationService()->create($stageObservation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un stage d'observation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);

        $form = $this->getAgentStageObservationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/stageobs/modifier', ['stageobs' => $stageObservation->getId()], [], true));
        $form->bind($stageObservation);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('STAGE_OBSERVATION');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentStageObservationService()->update($stageObservation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un stage d'observation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentStageObservationService()->historise($stageObservation);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $stageObservation->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function restaurerAction(): Response
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentStageObservationService()->restore($stageObservation);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $stageObservation->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function detruireAction(): ViewModel
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentStageObservationService()->delete($stageObservation);
            exit();
        }

        $vm = new ViewModel();
        if ($stageObservation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du stage d'observation #" . $stageObservation->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/stageobs/detruire', ["ppp" => $stageObservation->getId()], [], true),
            ]);
        }
        return $vm;
    }

}