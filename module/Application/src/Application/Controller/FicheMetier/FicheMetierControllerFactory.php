<?php

namespace Application\Controller\FicheMetier;

use Application\Form\FicheMetier\AssocierAgentForm;
use Application\Form\FicheMetier\AssocierMetierTypeForm;
use Application\Form\FicheMetier\AssocierPosteForm;
use Application\Form\FicheMetier\FicheMetierCreationForm;
use Application\Form\FicheMetier\SpecificitePosteForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Agent\AgentService;
use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         * @var AgentService $agentService
         * @var ActiviteService $activiteService
         */
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $activiteService = $manager->getServiceLocator()->get(ActiviteService::class);

        /**
         * @var AssocierAgentForm $associerAgentForm
         * @var AssocierMetierTypeForm $associerMetierTypeForm
         * @var AssocierPosteForm $associerPosteForm
         * @var FicheMetierCreationForm $ficheMetierCreationForm
         * @var SpecificitePosteForm $specificitePosteForm
         */
        $associerAgentForm = $manager->getServiceLocator()->get('FormElementManager')->get(AssocierAgentForm::class);
        $associerMetierTypeForm = $manager->getServiceLocator()->get('FormElementManager')->get(AssocierMetierTypeForm::class);
        $associerPosteForm = $manager->getServiceLocator()->get('FormElementManager')->get(AssocierPosteForm::class);
        $ficheMetierCreationForm = $manager->getServiceLocator()->get('FormElementManager')->get(FicheMetierCreationForm::class);
        $specificitePosteForm = $manager->getServiceLocator()->get('FormElementManager')->get(SpecificitePosteForm::class);

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();

        $controller->setFicheMetierService($ficheMetierService);
        $controller->setAgentService($agentService);
        $controller->setActiviteService($activiteService);

        $controller->setAssocierAgentForm($associerAgentForm);
        $controller->setAssocierMetierTypeForm($associerMetierTypeForm);
        $controller->setAssocierPosteForm($associerPosteForm);
        $controller->setFicherMetierCreationForm($ficheMetierCreationForm);
        $controller->setSpecificitePosteForm($specificitePosteForm);

        return $controller;
    }

}