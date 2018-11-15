<?php

namespace Application\Controller\Application;

use Application\Entity\Db\Application;
use Application\Form\Application\ApplicationForm;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ApplicationController  extends AbstractActionController {
    use ApplicationServiceAwareTrait;

    public function indexAction()
    {
        /** @var Application[] $activites */
        $applications = $this->getApplicationService()->getApplications('id');

        return new ViewModel([
            'applications' => $applications,
        ]);
    }

    public function creerAction()
    {
        /** @var Application $application */
        $application = new Application();

        /** @var ApplicationForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ApplicationForm::class);
        $form->bind($application);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getApplicationService()->create($application);
                $this->redirect()->toRoute('application');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editerAction()
    {
        /** @var Application $application */
        $applicationId = $this->params()->fromRoute('id');
        $application = $this->getApplicationService()->getApplication($applicationId);

        /** @var ApplicationForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ApplicationForm::class);
        $form->bind($application);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getApplicationService()->update($application);
                $this->redirect()->toRoute('application');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function effacerAction()
    {
        /** @var Application $application */
        $applicationId = $this->params()->fromRoute('id');
        $application = $this->getApplicationService()->getApplication($applicationId);

        $this->getApplicationService()->delete($application);
        $this->redirect()->toRoute('application');
    }

    public function changerStatusAction()
    {
        /** @var Application $application */
        $applicationId = $this->params()->fromRoute('id');
        $application = $this->getApplicationService()->getApplication($applicationId);

        $application->setActif( !$application->isActif() );

        $this->getApplicationService()->update($application);
        $this->redirect()->toRoute('application');
    }

    public function afficherAction()
    {
        /** @var Application $application */
        $applicationId = $this->params()->fromRoute('id');
        $application = $this->getApplicationService()->getApplication($applicationId);

        return new ViewModel([
            'title' => "Affichage de la description de l'application",
            'application' => $application,
        ]);
    }
}