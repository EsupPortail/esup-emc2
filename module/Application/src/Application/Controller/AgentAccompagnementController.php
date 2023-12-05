<?php

namespace Application\Controller;

use Application\Entity\Db\AgentAccompagnement;
use Application\Form\AgentAccompagnement\AgentAccompagnementFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAccompagnement\AgentAccompagnementServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Form\EtatFieldset\EtatFieldset;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

class AgentAccompagnementController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAccompagnementServiceAwareTrait;
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use AgentAccompagnementFormAwareTrait;

    public function ajouterAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $accompagnement = new AgentAccompagnement();
        $accompagnement->setAgent($agent);

        $form = $this->getAgentAccompagnementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/accompagnement/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($accompagnement);

        $categorie = $this->getEtatCategorieService()->getEtatCategorieByCode('ACCOMPAGNEMENT');
        $types = $this->getEtatTypeService()->getEtatsTypesByCategorie($categorie);

        /** @var EtatFieldset $selectEtat */
        $selectEtat = $form->get('etat');
        $selectEtat->resetEtats($types);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentAccompagnementService()->create($accompagnement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un accompagnement",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);

        $form = $this->getAgentAccompagnementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/accompagnement/modifier', ['accompagnement' => $accompagnement->getId()], [], true));
        $form->bind($accompagnement);

        $categorie = $this->getEtatCategorieService()->getEtatCategorieByCode('ACCOMPAGNEMENT');
        $types = $this->getEtatTypeService()->getEtatsTypesByCategorie($categorie);
        /** @var EtatFieldset $selectEtat */
        $selectEtat = $form->get('etat');
        $selectEtat->resetEtats($types);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentAccompagnementService()->update($accompagnement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un accompagnement",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentAccompagnementService()->historise($accompagnement);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $accompagnement->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function restaurerAction(): Response
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentAccompagnementService()->restore($accompagnement);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $accompagnement->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function detruireAction(): ViewModel
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentAccompagnementService()->delete($accompagnement);
            exit();
        }

        $vm = new ViewModel();
        if ($accompagnement !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'accompagnement #" . $accompagnement->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/accompagnement/detruire', ["accompagnement" => $accompagnement->getId()], [], true),
            ]);
        }
        return $vm;
    }
}