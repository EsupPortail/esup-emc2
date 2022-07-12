<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FormationInstanceInscritController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use RenduServiceAwareTrait;
    use EtatServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;

    use SelectionAgentFormAwareTrait;

    public function ajouterAgentAction() : ViewModel
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
                    $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_VALIDATION_INSCRIPTION));
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

    public function historiserAgentAction() : Response
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $inscrit->setListe(null);
        $this->getFormationInstanceInscritService()->historise($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être retiré&middot;e des listes.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function restaurerAgentAction() : Response
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $liste = $inscrit->getInstance()->getListeDisponible();

        if ($liste !== null) {
            $inscrit->setListe($liste);
            $this->getFormationInstanceInscritService()->restore($inscrit);
        }
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function supprimerAgentAction() : ViewModel
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

    public function envoyerListePrincipaleAction() : Response
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $inscrit->setListe(FormationInstanceInscrit::PRINCIPALE);
        $this->getFormationInstanceInscritService()->update($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être ajouté&middot;e en liste principale.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function envoyerListeComplementaireAction() : Response
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $inscrit->setListe(FormationInstanceInscrit::COMPLEMENTAIRE);
        $this->getFormationInstanceInscritService()->update($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être ajouté&middot;e en liste complémentaire.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function listeFormationsInstancesAction() : ViewModel
    {
        $instances = $this->getFormationInstanceService()->getFormationsInstancesByEtat(FormationInstance::ETAT_INSCRIPTION_OUVERTE);
        //$instances = array_filter($instances, function (FormationInstance $a) { return $a->isAutoInscription();});
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);

        $inscriptions = $this->getFormationInstanceInscritService()->getFormationsByInscrit($agent);
        $formations = $this->getFormationInstanceInscritService()->getFormationsBySuivies($agent);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode('PARCOURS_ENTREE_TEXTE', []);
        return new ViewModel([
            'instances' => $instances,
            'inscriptions' => $inscriptions,
            'formations' => $formations,
            'agent' => $agent,
            'texteParcours' => $rendu->getCorps(),
        ]);
    }

    public function inscriptionAction()  : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $inscrit = new FormationInstanceInscrit();
        $inscrit->setInstance($instance);
        $inscrit->setAgent($agent);
        $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_DEMANDE_INSCRIPTION));
        $this->getFormationInstanceInscritService()->create($inscrit);
        $this->flashMessenger()->addSuccessMessage("Demande d'inscription faite.");

        $this->getNotificationService()->triggerInscriptionAgent($agent, $instance);

        return $this->redirect()->toRoute('liste-formations-instances', [], ['fragment' => 'instances'], true);
    }

    public function desinscriptionAction() : ViewModel
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $instance = $inscrit->getInstance();
        $agent = $inscrit->getAgent();
        $user = $this->getUserService()->getConnectedUser();

        $break = false;
        if ($agent->getUtilisateur() !== $user) {
            $this->flashMessenger()->addErrorMessage("L'utilisateur connecté ne correspond à l'agent en train de se déinscrire !");
            $break = true;
        }

        if (!$break) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                if ($data["reponse"] === "oui") {
                    $inscrit->setComplement($data["complement"]);
                    $this->getFormationInstanceInscritService()->update($inscrit);
                    $this->getFormationInstanceInscritService()->historise($inscrit);
                    $this->flashMessenger()->addSuccessMessage("Inscription annulée.");
                }
            }
        }

        $intitule = $instance->getFormation()->getLibelle();
        $periode = $instance->getDebut()." au " .$instance->getFin() ;
        $vm = new ViewModel([
            'title' => "Desinscription à la formation " . $intitule,
            'text' => "Je confirme me désinscrire de la formation ". $intitule ." du ". $periode.".",
            'action' => $this->url()->fromRoute('formation-instance/desinscription', ["inscrit" => $inscrit->getId()], [], true),
            'complement' => 'oui',
        ]);
        $vm->setTemplate('formation/default/confirmation');
        return $vm;

    }

    public function validerResponsableAction() : ViewModel
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $agent = $inscrit->getAgent();
        $instance = $inscrit->getInstance();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if ($data["reponse"] === "oui") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_VALIDATION_RESPONSABLE));

                $this->getNotificationService()->triggerResponsableValidation($inscrit);
            }
            if ($data["reponse"] === "non") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_REFUS_INSCRIPTION));
                $inscrit->setComplement($data["complement"]);
                $this->getFormationInstanceInscritService()->update($inscrit);

                $this->getNotificationService()->triggerResponsableRefus($inscrit);
            }
            $this->getFormationInstanceInscritService()->update($inscrit);
            exit();
        }

        $denomination = $agent->getDenomination();
        $intitule = $instance->getFormation()->getLibelle();
        $periode = $instance->getDebut()." au " .$instance->getFin() ;
        $vm = new ViewModel([
            'title' => "Validation par le responsable hiérarchique",
            'text' => "Je valide l'inscription de ". $denomination ." à la formation ". $intitule ." du ". $periode.".",
            'action' => $this->url()->fromRoute('formation-instance/valider-responsable', ["isncrit" => $inscrit->getId()], [], true),
            'complement' => 'non',
        ]);
        $vm->setTemplate('formation/default/confirmation');
        return $vm;

    }

    public function validerDrhAction() : ViewModel
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $agent = $inscrit->getAgent();
        $instance = $inscrit->getInstance();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_VALIDATION_INSCRIPTION));

                $this->getNotificationService()->triggerDrhValidation($inscrit);

                if ($inscrit->getListe() === null AND !$instance->isListePrincipaleComplete()) { $inscrit->setListe('principale'); }
                if ($inscrit->getListe() === null AND !$instance->isListeComplementaireComplete()) { $inscrit->setListe('complementaire'); }
            }
            if ($data["reponse"] === "non") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_REFUS_INSCRIPTION));
                $inscrit->setComplement($data["complement"]);
                $this->getFormationInstanceInscritService()->update($inscrit);

                $this->getNotificationService()->triggerDrhRefus($inscrit);
            }
            $this->getFormationInstanceInscritService()->update($inscrit);
            exit();
        }

        $denomination = $agent->getDenomination();
        $intitule = $instance->getFormation()->getLibelle();
        $periode = $instance->getDebut()." au " .$instance->getFin() ;
        $vm = new ViewModel([
            'title' => "Validation par la direction des ressources humaines",
            'text' => "Je valide l'inscription de ". $denomination ." à la formation ". $intitule ." du ". $periode.".",
            'action' => $this->url()->fromRoute('formation-instance/valider-drh', ["isncrit" => $inscrit->getId()], [], true),
            'complement' => 'non',
        ]);
        $vm->setTemplate('formation/default/confirmation');
        return $vm;
    }
}