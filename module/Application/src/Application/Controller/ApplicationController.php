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
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Application\ApplicationGroupeServiceAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
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

    /** APPLICATION ***************************************************************************************************/

    public function indexAction() : ViewModel
    {
        $groupeId = $this->params()->fromQuery('groupe');
        $activite = $this->params()->fromQuery('activite');
        /**
         * @var Application[] $applications
         * @var ApplicationGroupe[] $groupes
         */
        if ($groupeId !== null AND $groupeId !== "") {
            $groupe = $this->getApplicationGroupeService()->getApplicationGroupe($groupeId);
            $applications = $this->getApplicationService()->getApplicationsGyGroupe($groupe);
        } else {
            $applications = $this->getApplicationService()->getApplications();
        }
        $groupes = $this->getApplicationGroupeService()->getApplicationsGroupes('libelle');

        if ($activite === "1") $applications = array_filter($applications, function (Application $a) { return $a->isActif();});
        if ($activite === "0") $applications = array_filter($applications, function (Application $a) { return !$a->isActif();});

        return new ViewModel([
            'applications' => $applications,
            'activite' => $activite,
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

    public function afficherAction() : ViewModel
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        return new ViewModel([
            'title' => "Description de l'application",
            'application' => $application,
        ]);
    }

    /** GESTION DES COMPETENCES ELEMENTS ==> Faire CONTROLLER ? *******************************************************/

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

        $application = null;
        if ($applicationId = $this->params()->fromQuery('application')) {
            $application = $this->getApplicationService()->getApplication($applicationId);
        }

        if ($hasApplicationElement !== null) {
            $form = $this->getApplicationElementForm();
            $element = new ApplicationElement();
            if ($application !== null) {$element->setApplication($application);}
            if ($clef === 'masquer') $form->masquerClef();

            $form->setAttribute('action', $this->url()->fromRoute('application/ajouter-application-element', ['type' => $type, 'id' => $hasApplicationElement->getId()], [], true));
            $form->bind($element);


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
}