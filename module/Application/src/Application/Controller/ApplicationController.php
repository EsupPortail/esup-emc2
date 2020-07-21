<?php

namespace Application\Controller;

use Application\Entity\Db\Application;
use Application\Entity\Db\ApplicationGroupe;
use Application\Form\Application\ApplicationForm;
use Application\Form\Application\ApplicationFormAwareTrait;
use Application\Form\ApplicationGroupe\ApplicationGroupeFormAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\Application\ApplicationGroupeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ApplicationController  extends AbstractActionController {
    use ApplicationServiceAwareTrait;
    use ApplicationGroupeServiceAwareTrait;
    use ApplicationFormAwareTrait;
    use ApplicationGroupeFormAwareTrait;

    /** APPLICATION ***************************************************************************************************/

    public function indexAction()
    {
        /**
         * @var Application[] $activites
         * @var ApplicationGroupe[] $groupes
         */
        $applications = $this->getApplicationService()->getApplications('libelle');
        $groupes = $this->getApplicationGroupeService()->getApplicationsGroupes('libelle');

        return new ViewModel([
            'applications' => $applications,
            'groupes' => $groupes,
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
}