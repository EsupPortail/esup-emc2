<?php

namespace Application\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Application;
use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\ApplicationGroupe;
use Application\Entity\Db\FicheMetier;
use Application\Form\Application\ApplicationForm;
use Application\Form\Application\ApplicationFormAwareTrait;
use Application\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Application\Form\ApplicationGroupe\ApplicationGroupeFormAwareTrait;
use Application\Form\ModifierNiveau\ModifierNiveauFormAwareTrait;
use Application\Form\SelectionCompetenceMaitrise\SelectionCompetenceMaitriseFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\Application\ApplicationGroupeServiceAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Form\Element\Hidden;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ApplicationController  extends AbstractActionController {
    use ApplicationServiceAwareTrait;
    use ApplicationGroupeServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use ApplicationFormAwareTrait;
    use ApplicationElementFormAwareTrait;
    use ApplicationGroupeFormAwareTrait;
    use SelectionCompetenceMaitriseFormAwareTrait;

    /** APPLICATION ***************************************************************************************************/

    public function indexAction()
    {
        $groupeId = $this->params()->fromQuery('groupe');
        /**
         * @var Application[] $applications
         * @var ApplicationGroupe[] $groupes
         */

        $applications = [];
        if ($groupeId !== null AND $groupeId !== "") {
            $groupe = $this->getApplicationGroupeService()->getApplicationGroupe($groupeId);
            $applications = $this->getApplicationService()->getApplicationsGyGroupe($groupe);
        } else {
            $applications = $this->getApplicationService()->getApplications();
        }
        $groupes = $this->getApplicationGroupeService()->getApplicationsGroupes('libelle');

        return new ViewModel([
            'applications' => $applications,
            'groupes' => $groupes,
            'groupeSelected' => $groupeId,
        ]);
}

    public function creerAction()
    {
        /** @var Application $application */
        $application = new Application();

        /** @var ApplicationForm $form */
        $form = $this->getApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/creer', [], [], true));
        $form->bind($application);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getApplicationService()->create($application);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une application',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction()
    {
        /** @var Application $application */
        $applicationId = $this->params()->fromRoute('id');
        $application = $this->getApplicationService()->getApplication($applicationId);

        /** @var ApplicationForm $form */
        $form = $this->getApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/editer', ['id' => $application->getId()], [], true));
        $form->bind($application);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getApplicationService()->update($application);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une application',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerAction()
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getApplicationService()->delete($application);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($application !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application [" . $application->getLibelle(). "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('application/effacer', ["id" => $application->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function changerStatusAction()
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        $application->setActif( !$application->isActif() );

        $this->getApplicationService()->update($application);
        return $this->redirect()->toRoute('application');
    }

    public function afficherAction()
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        return new ViewModel([
            'title' => "Description de l'application",
            'application' => $application,
        ]);
    }

    /** GROUPE ********************************************************************************************************/

    public function ajouterGroupeAction()
    {
        $groupe = new ApplicationGroupe();

        $form = $this->getApplicationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/groupe/ajouter',[],[], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationGroupeService()->create($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un groupe d'application",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherGroupeAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);

        return new ViewModel([
            'title' => "Affichage du groupe d'application",
            'groupe' => $groupe,
        ]);
    }

    public function modifierGroupeAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);

        $form = $this->getApplicationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/groupe/editer',[],[], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationGroupeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un groupe d'application",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserGroupeAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);
        $this->getApplicationGroupeService()->historise($groupe);
        return $this->redirect()->toRoute('application', [], ['fragment' => 'groupe'], true);
    }

    public function restaurerGroupeAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);
        $this->getApplicationGroupeService()->restore($groupe);
        return $this->redirect()->toRoute('application', [], ['fragment' => 'groupe'], true);
    }

    public function detruireGroupeAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getApplicationGroupeService()->delete($groupe);
            exit();
        }

        $vm = new ViewModel();
        if ($groupe !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du groupe d'application [" . $groupe->getLibelle(). "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('application/groupe/detruire', ["application-groupe" => $groupe->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** GESTION DES COMPETENCES ELEMENTS ==> Faire CONTROLLER ? *******************************************************/

    public function afficherApplicationElementAction()
    {
        $element = $this->getApplicationElementService()->getRequestedApplicationElement($this);
        return new ViewModel([
            'title' => "Affichage de l'application [".$element->getApplication()->getLibelle()."]",
            'applicationElement' => $element,
        ]);
    }

    public function ajouterApplicationElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $hasApplicationElement = null;
        switch($type) {
            case Agent::class : $hasApplicationElement = $this->getAgentService()->getRequestedAgent($this, 'id');
                break;
            case FicheMetier::class : $hasApplicationElement = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');
                break;
        }
        $clef=$this->params()->fromRoute('clef');

        if ($hasApplicationElement !== null) {
            $element = new ApplicationElement();

            $form = $this->getApplicationElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('application/ajouter-application-element', ['type' => $type, 'id' => $hasApplicationElement->getId()], [], true));
            $form->bind($element);
            if ($clef === 'masquer') $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getApplicationElementService()->create($element);
                    $hasApplicationElement->addApplicationElement($element);
                    switch($type) {
                        case Agent::class :
                            $this->getAgentService()->update($hasApplicationElement);
                            break;
                        case FicheMetier::class :
                            $this->getFicheMetierService()->update($hasApplicationElement);
                            break;
                    }
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une application",
                'form' => $form,
            ]);
            $vm->setTemplate('application/default/default-form');
            return $vm;
        }
        exit();
    }

    public function supprimerApplicationElementAction()
    {
        $element = $this->getApplicationElementService()->getRequestedApplicationElement($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getApplicationElementService()->delete($element);
            exit();
        }

        $vm = new ViewModel();
        if ($element !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application  " . $element->getApplication()->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('application/supprimer-application-element', ["application-element" => $element->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Niveau de maitrise d'un  */
    public function changerNiveauAction() {
        $element = $this->getApplicationElementService()->getRequestedApplicationElement($this);
        $clef=$this->params()->fromRoute('clef');

        $form = $this->getSelectionCompetenceMaitriseForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/changer-niveau', ['application-element' => $element->getId(), 'clef' => $clef], [], true));
        $form->bind($element);
        if ($clef === 'masquer') $form->masquerClef();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationElementService()->update($element);
            }
        }

        $vm = new ViewModel([
            'title' => "Changer le niveau de maîtrise",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }
}