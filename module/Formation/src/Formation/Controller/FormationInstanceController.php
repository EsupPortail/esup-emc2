<?php

namespace Formation\Controller;

use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Formation\Entity\Db\FormationInstance;
use Formation\Form\FormationInstance\FormationInstanceFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
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
    use ParametreServiceAwareTrait;
    use FormationInstanceFormAwareTrait;

    /** NB: par defaut les instances de formation sont toutes en autoinscription **************************************/

    public function ajouterAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $instance = new FormationInstance();
        $instance->setType(FormationInstance::TYPE_INTERNE);
        $instance->setAutoInscription(true);
        $instance->setNbPlacePrincipale(0);
        $instance->setNbPlaceComplementaire(0);
        $instance->setFormation($formation);
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_CREATION_EN_COURS));

        $this->getFormationInstanceService()->create($instance);
        $instance->setSource("EMC2");
        $instance->setIdSource(($formation->getIdSource())?(($formation->getIdSource())."-".$instance->getId()):($formation->getId()."-".$instance->getId()));
        $this->getFormationInstanceService()->update($instance);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function afficherAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $mails = $this->getMailingService()->getMailsByAttachement(FormationInstance::class, $instance->getId());

        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
            'mails' => $mails,
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
        $this->getFormationInstanceService()->ouvrirInscription($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function fermerInscriptionAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->fermerInscription($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function envoyerConvocationAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->envoyerConvocation($instance);
        $this->getFormationInstanceService()->envoyerEmargement($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function demanderRetourAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->demanderRetour($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function cloturerAction()
    {
          $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
          $this->getFormationInstanceService()->cloturer($instance);
          return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }
}