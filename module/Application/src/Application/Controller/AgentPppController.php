<?php

namespace Application\Controller;

use Application\Entity\Db\AgentPPP;
use Application\Form\AgentPPP\AgentPPPFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentPPP\AgentPPPServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

class AgentPppController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentPPPServiceAwareTrait;
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;

    use AgentPPPFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $ppp = new AgentPPP();
        $ppp->setAgent($agent);

        $form = $this->getAgentPPPForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ppp/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($ppp);

        $categorie = $this->getEtatCategorieService()->getEtatCategorieByCode('PPP');
        $types = $this->getEtatTypeService()->getEtatsTypesByCategorie($categorie);
        $form->get('etat')->resetEtats($types);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentPPPService()->create($ppp);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un projet professionnel personnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);

        $form = $this->getAgentPPPForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ppp/modifier', ['ppp' => $ppp->getId()], [], true));
        $form->bind($ppp);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('PPP');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentPPPService()->update($ppp);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un projet professionnel personnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentPPPService()->historise($ppp);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $ppp->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function restaurerAction() : Response
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentPPPService()->restore($ppp);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $ppp->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function detruireAction() : ViewModel
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentPPPService()->delete($ppp);
            exit();
        }

        $vm = new ViewModel();
        if ($ppp !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du projet professionnel personnel #" . $ppp->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/ppp/detruire', ["ppp" => $ppp->getId()], [], true),
            ]);
        }
        return $vm;
    }
}