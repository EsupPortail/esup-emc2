<?php

namespace Application\Controller;

use Application\Entity\Db\AgentTutorat;
use Application\Form\AgentTutorat\AgentTutoratFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentTutorat\AgentTutoratServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

class AgentTutoratController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentTutoratServiceAwareTrait;
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use AgentTutoratFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $tutorat = new AgentTutorat();
        $tutorat->setAgent($agent);

        $form = $this->getAgentTutoratForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/tutorat/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($tutorat);

        $categorie = $this->getEtatCategorieService()->getEtatCategorieByCode('TUTORAT');
        $types = $this->getEtatTypeService()->getEtatsTypesByCategorie($categorie);
        $form->get('etat')->resetEtats($types);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentTutoratService()->create($tutorat);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un tutorat",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);

        $form = $this->getAgentTutoratForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/tutorat/modifier', ['tutorat' => $tutorat->getId()], [], true));
        $form->bind($tutorat);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('TUTORAT');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentTutoratService()->update($tutorat);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un tutorat",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentTutoratService()->historise($tutorat);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $tutorat->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function restaurerAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentTutoratService()->restore($tutorat);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $tutorat->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function detruireAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentTutoratService()->delete($tutorat);
            exit();
        }

        $vm = new ViewModel();
        if ($tutorat !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du tutorat #" . $tutorat->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/tutorat/detruire', ["tutorat" => $tutorat->getId()], [], true),
            ]);
        }
        return $vm;
    }

}