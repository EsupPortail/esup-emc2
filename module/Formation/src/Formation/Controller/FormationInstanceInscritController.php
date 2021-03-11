<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */
class FormationInstanceInscritController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use UserServiceAwareTrait;

    use SelectionAgentFormAwareTrait;


    public function ajouterAgentAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $inscrit = new FormationInstanceInscrit();
        $inscrit->setInstance($instance);

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-agent', ['formulaire-instance' => $instance->getId()], [], true));
        $form->bind($inscrit);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!$instance->hasAgent($inscrit->getAgent())) {
                    $inscrit->setListe($instance->getListeDisponible());
                    $this->getFormationInstanceInscritService()->create($inscrit);

                    $texte = ($instance->getListeDisponible() === FormationInstanceInscrit::PRINCIPALE) ? "principale" : "complémentaire";
                    $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être ajouté&middot;e en <strong>liste " . $texte . "</strong>.");
                } else {
                    $this->flashMessenger()->addErrorMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> est déjà inscrit&middot;e à l'instance de formation.");
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un agent pour l'instance de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $inscrit->setListe(null);
        $this->getFormationInstanceInscritService()->historise($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être retiré&middot;e des listes.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function restaurerAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $liste = $inscrit->getInstance()->getListeDisponible();

        if ($liste !== null) {
            $inscrit->setListe($liste);
            $this->getFormationInstanceInscritService()->restore($inscrit);
        }
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function supprimerAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceInscritService()->delete($inscrit);
            exit();
        }

        $vm = new ViewModel();
        if ($inscrit !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'inscription de [" . $inscrit->getAgent()->getDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-agent', ["inscrit" => $inscrit->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function envoyerListePrincipaleAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $inscrit->setListe(FormationInstanceInscrit::PRINCIPALE);
        $this->getFormationInstanceInscritService()->update($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être ajouté&middot;e en liste principale.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function envoyerListeComplementaireAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $inscrit->setListe(FormationInstanceInscrit::COMPLEMENTAIRE);
        $this->getFormationInstanceInscritService()->update($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être ajouté&middot;e en liste complémentaire.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function listeFormationsInstancesAction()
    {
        $instances = $this->getFormationInstanceService()->getFormationsInstancesByEtat(FormationInstance::ETAT_INSCRIPTION_OUVERTE);
        $instances = array_filter($instances, function (FormationInstance $a) { return $a->isAutoInscription();});
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);

        $inscriptions = $this->getFormationInstanceInscritService()->getFormationsByInscrit($agent);

        return new ViewModel([
            'instances' => $instances,
            'inscriptions' => $inscriptions,
            'agent' => $agent,
        ]);
    }

    public function inscriptionAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $user = $this->getUserService()->getConnectedUser();

        $break = false;
        $liste = $instance->getListeDisponible();
        if ($agent->getUtilisateur() !== $user) {
            $this->flashMessenger()->addErrorMessage("L'utilisateur connecté ne correspond à l'agent en train de s'inscrire !");
            $break = true;
        }
        if ($liste === null) {
            $this->flashMessenger()->addErrorMessage("Plus de place disponible sur aucune des listes de cette action de formation.");
            $break = true;
        }

        if (!$break) {
            $inscrit = new FormationInstanceInscrit();
            $inscrit->setInstance($instance);
            $inscrit->setAgent($agent);
            $inscrit->setListe($liste);
            $this->getFormationInstanceInscritService()->create($inscrit);
            $this->flashMessenger()->addSuccessMessage("Inscription effectué sur la liste ".$liste.".");
        }

        return $this->redirect()->toRoute('liste-formations-instances', [], ['fragment' => 'instances'], true);
    }

    public function desinscriptionAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $agent = $inscrit->getAgent();
        $user = $this->getUserService()->getConnectedUser();

        $break = false;
        if ($agent->getUtilisateur() !== $user) {
            $this->flashMessenger()->addErrorMessage("L'utilisateur connecté ne correspond à l'agent en train de se déinscrire !");
            $break = true;
        }

        if (!$break) {
            $this->getFormationInstanceInscritService()->historise($inscrit);
            $this->flashMessenger()->addSuccessMessage("Inscription annulée.");
        }

        return $this->redirect()->toRoute('liste-formations-instances', [], ['fragment' => 'inscriptions'], true);
    }
}