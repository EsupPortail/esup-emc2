<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Contenu\ContenuServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */
class FormationInstanceInscritController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use ContenuServiceAwareTrait;
    use EtatServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
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
        //$instances = array_filter($instances, function (FormationInstance $a) { return $a->isAutoInscription();});
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

        $inscrit = new FormationInstanceInscrit();
        $inscrit->setInstance($instance);
        $inscrit->setAgent($agent);
        $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_DEMANDE_INSCRIPTION));
        $this->getFormationInstanceInscritService()->create($inscrit);
        $this->flashMessenger()->addSuccessMessage("Demande d'inscription faite.");

        $vars = ['agent' => $agent, 'formation' => $instance];
        $contenu = $this->getContenuService()->generateContenu("FORMATION_DEMANDE_INSCRIPTION", $vars);

        $email = $this->getParametreService()->getParametreByCode('FORMATION','EMAIL')->getValeur();
        $mail = $this->getMailService()->sendMail($email, $contenu->getSujet(), $contenu->getCorps());
        $mail->setEntity($instance);
        $mail->setTemplateCode($contenu->getTemplate()->getCode());
        $this->getMailService()->update($mail);

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

    public function validerResponsableAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $agent = $inscrit->getAgent();
        $instance = $inscrit->getInstance();
        $denomination = $agent->getDenomination();
        $intitule = $instance->getFormation()->getLibelle();
        $periode = $instance->getDebut()." au " .$instance->getFin() ;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $emailCCC = $this->getParametreService()->getParametreByCode('FORMATION','EMAIL')->getValeur();
            $emailAgent = $agent->getEmail();

            if ($data["reponse"] === "oui") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_VALIDATION_RESPONSABLE));

                $vars = ['agent' => $agent, 'formation' => $instance];
                $contenu = $this->getContenuService()->generateContenu("FORMATION_VALIDATION_RESPONSABLE", $vars);
                $mail = $this->getMailService()->sendMail($emailCCC .",".$emailAgent, $contenu->getSujet(), $contenu->getCorps());
                $mail->setEntity($instance);
                $mail->setTemplateCode($contenu->getTemplate()->getCode());
                $this->getMailService()->update($mail);
            }
            if ($data["reponse"] === "non") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_REFUS_INSCRIPTION));

                $vars = ['agent' => $agent, 'formation' => $instance, 'complement' => $data["completement"]];
                $contenu = $this->getContenuService()->generateContenu("FORMATION_REFUS_INSCRIPTION", $vars);
                $mail = $this->getMailService()->sendMail($emailCCC .",".$emailAgent, $contenu->getSujet(), $contenu->getCorps());
                $mail->setEntity($instance);
                $mail->setTemplateCode($contenu->getTemplate()->getCode());
                $this->getMailService()->update($mail);
            }
            $this->getFormationInstanceInscritService()->update($inscrit);
            exit();
        }

        $vm = new ViewModel([
            'title' => "Validation par le responsable hiérarchique",
            'text' => "Je valide l'inscription de ". $denomination ." à la formation ". $intitule ." du ". $periode.".",
            'action' => $this->url()->fromRoute('formation-instance/valider-responsable', ["isncrit" => $inscrit->getId()], [], true),
            'complement' => true,
        ]);
        $vm->setTemplate('formation/default/confirmation');
        return $vm;

    }

    public function validerDrhAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $agent = $inscrit->getAgent();
        $instance = $inscrit->getInstance();
        $denomination = $agent->getDenomination();
        $intitule = $instance->getFormation()->getLibelle();
        $periode = $instance->getDebut()." au " .$instance->getFin() ;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_VALIDATION_INSCRIPTION));

                $vars = ['agent' => $agent, 'formation' => $instance];
                $contenu = $this->getContenuService()->generateContenu("FORMATION_VALIDATION_DRH", $vars);
                $mail = $this->getMailService()->sendMail($agent->getEmail(), $contenu->getSujet(), $contenu->getCorps());
                $mail->setEntity($instance);
                $mail->setTemplateCode($contenu->getTemplate()->getCode());
                $this->getMailService()->update($mail);

                if ($inscrit->getListe() === null AND !$instance->isListePrincipaleComplete()) { $inscrit->setListe('principale'); }
                if ($inscrit->getListe() === null AND !$instance->isListeComplementaireComplete()) { $inscrit->setListe('complementaire'); }
            }
            if ($data["reponse"] === "non") {
                $inscrit->setEtat($this->getEtatService()->getEtatByCode(FormationInstanceInscrit::ETAT_REFUS_INSCRIPTION));

                $vars = ['agent' => $agent, 'formation' => $instance, 'complement' => $data["completement"]];
                $contenu = $this->getContenuService()->generateContenu("FORMATION_REFUS_INSCRIPTION", $vars);
                $mail = $this->getMailService()->sendMail($agent->getEmail(), $contenu->getSujet(), $contenu->getCorps());
                $mail->setEntity($instance);
                $mail->setTemplateCode($contenu->getTemplate()->getCode());
                $this->getMailService()->update($mail);
            }
            $this->getFormationInstanceInscritService()->update($inscrit);
            exit();
        }

        $vm = new ViewModel([
            'title' => "Validation par la direction des ressources humaines",
            'text' => "Je valide l'inscription de ". $denomination ." à la formation ". $intitule ." du ". $periode.".",
            'action' => $this->url()->fromRoute('formation-instance/valider-drh', ["isncrit" => $inscrit->getId()], [], true),
            'complement' => true,
        ]);
        $vm->setTemplate('formation/default/confirmation');
        return $vm;
    }
}