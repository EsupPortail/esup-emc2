<?php

namespace Application\Controller\RessourceRh;

use Application\Form\RessourceRh\AgentStatusForm;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\FonctionForm;
use Application\Form\RessourceRh\GradeForm;
use Application\Form\RessourceRh\MetierFamilleForm;
use Application\Form\RessourceRh\MetierForm;
use Application\Service\RessourceRh\RessourceRhService;
use Zend\Mvc\Controller\ControllerManager;

class RessourceRhControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var RessourceRhService $ressourceService
         */
        $ressourceService    = $manager->getServiceLocator()->get(RessourceRhService::class);

        /**
         * @var AgentStatusForm $agentStatusForm
         * @var CorpsForm $corpsForm
         * @var CorrespondanceForm $correspondanceForm
         * @var DomaineForm $domaineForm
         * @var FonctionForm $fonctionForm
         * @var GradeForm $gradeForm
         * @var MetierFamilleForm $metierFamilleForm
         * @var MetierForm $metierForm
         */
        $agentStatusForm    = $manager->getServiceLocator()->get('FormElementManager')->get(AgentStatusForm::class);
        $corpsForm          = $manager->getServiceLocator()->get('FormElementManager')->get(CorpsForm::class);
        $correspondanceForm = $manager->getServiceLocator()->get('FormElementManager')->get(CorrespondanceForm::class);
        $domaineForm        = $manager->getServiceLocator()->get('FormElementManager')->get(DomaineForm::class);
        $fonctionForm       = $manager->getServiceLocator()->get('FormElementManager')->get(FonctionForm::class);
        $gradeForm          = $manager->getServiceLocator()->get('FormElementManager')->get(GradeForm::class);
        $metierFamilleForm  = $manager->getServiceLocator()->get('FormElementManager')->get(MetierFamilleForm::class);
        $metierForm         = $manager->getServiceLocator()->get('FormElementManager')->get(MetierForm::class);

        /** @var RessourceRhController $controller */
        $controller = new RessourceRhController();

        $controller->setRessourceRhService($ressourceService);

        $controller->setAgentStatusForm($agentStatusForm);
        $controller->setCorpsForm($corpsForm);
        $controller->setCorrespondanceForm($correspondanceForm);
        $controller->setDomaineForm($domaineForm);
        $controller->setFonctionForm($fonctionForm);
        $controller->setGradeForm($gradeForm);
        $controller->setMetierFamilleForm($metierFamilleForm);
        $controller->setMetierForm($metierForm);
        return $controller;
    }

}