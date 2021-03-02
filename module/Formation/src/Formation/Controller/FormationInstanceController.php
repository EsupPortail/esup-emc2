<?php

namespace Formation\Controller;

use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use DateInterval;
use DateTime;
use Formation\Entity\Db\FormationInstance;
use Formation\Form\FormationInstance\FormationInstanceFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Validator\Date;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */
class FormationInstanceController extends AbstractActionController
{
    use EtatServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use MailingServiceAwareTrait;
    use FormationInstanceFormAwareTrait;


    public function ajouterAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $instance = new FormationInstance();
        $instance->setNbPlacePrincipale(0);
        $instance->setNbPlaceComplementaire(0);
        $instance->setFormation($formation);
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_CREATION_EN_COURS));

        $this->getFormationInstanceService()->create($instance);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function afficherAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
        ]);
    }

    public function modifierAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        return new ViewModel([
            'instance' => $instance,
            'mode' => "modification",
        ]);
    }

    public function historiserAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->historise($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function restaurerAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->restore($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function supprimerAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceService()->delete($instance);
            exit();
        }

        $vm = new ViewModel();
        if ($instance !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une instance de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer', ["formation-instance" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierInformationsAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $form = $this->getFormationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-informations', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($instance);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceService()->update($instance);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification des informations de l'instance",
            'form' => $form,
        ]);
        return $vm;
    }

    /** Question suite à la formation */

    public function renseignerQuestionnaireAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        if ($inscrit->getQuestionnaire() === null) {
            $questionnaire = $this->getFormulaireInstanceService()->createInstance('QUESTIONNAIRE_FORMATION');
            $inscrit->setQuestionnaire($questionnaire);
            $this->getFormationInstanceInscritService()->update($inscrit);
        }

        return new ViewModel([
            'inscrit' => $inscrit,
        ]);
    }

    public function ouvrirInscriptionAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        if ($instance->getEtat()->getCode() === FormationInstance::ETAT_CREATION_EN_COURS) {
            $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_INSCRIPTION_OUVERTE));
            $this->getFormationInstanceService()->update($instance);
            $this->getMailingService()->sendMailType("FORMATION_INSCRIPTION_OUVERTE", ['formation-instance' => $instance, 'mailing' => 'ZZZunicaen-biats@unicaen.fr']);
        }

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function fermerInscriptionAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        if ($instance->getEtat()->getCode() === FormationInstance::ETAT_INSCRIPTION_OUVERTE) {
            $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_INSCRIPTION_FERMEE));
            $this->getFormationInstanceService()->update($instance);
            $this->getMailingService()->sendMailType("FORMATION_LISTE_PRINCIPALE", ['formation-instance' => $instance, 'mailing' => 'ZZZunicaen-biats@unicaen.fr']);
            $this->getMailingService()->sendMailType("FORMATION_LISTE_SECONDAIRE", ['formation-instance' => $instance, 'mailing' => 'ZZZunicaen-biats@unicaen.fr']);
        }

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function convoquerAction()
    {
        $go = (new DateTime())->sub(new DateInterval('P2D'));
        $instances = $this->getFormationInstanceService()->getFormationsInstancesByEtat(FormationInstance::ETAT_INSCRIPTION_FERMEE);
        foreach ($instances as $instance) {
            if ($instance->getDebut() < $go ) {
                $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_FORMATION_CONVOCATION));
                $this->getFormationInstanceService()->update($instance);
                foreach ($instance->getListePrincipale() as $inscrit) {
                    $this->getMailingService()->sendMailType("FORMATION_CONVOCATION", ['formation-instance' => $instance, 'agent' => $inscrit->getAgent(), 'mailing' => $inscrit->getAgent()->getEmail()]);
                }
                echo "Action de formation #" . $instance->getId() ." - " . $instance->getFormation()->getLibelle() . " : Envoi des convocations.\n";
            }
        }
    }

    public function questionnerAction()
    {
        $go = (new DateTime())->sub(new DateInterval('P2D'));
        $instances = $this->getFormationInstanceService()->getFormationsInstancesByEtat(FormationInstance::ETAT_FORMATION_CONVOCATION);
        foreach ($instances as $instance) {
            if ($instance->getFin() > $go ) {
                $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_ATTENTE_RETOURS));
                $this->getFormationInstanceService()->update($instance);
                foreach ($instance->getListePrincipale() as $inscrit) {
                    $this->getMailingService()->sendMailType("FORMATION_RETOURS", ['formation-instance' => $instance, 'agent' => $inscrit->getAgent(), 'mailing' => $inscrit->getAgent()->getEmail()]);
                }
                echo "Action de formation #" . $instance->getId() ." - " . $instance->getFormation()->getLibelle() . " : Attente des retours.\n";
            }
        }
    }
}