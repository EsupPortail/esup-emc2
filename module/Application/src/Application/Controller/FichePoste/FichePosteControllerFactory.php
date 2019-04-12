<?php

namespace Application\Controller\FichePoste;

use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\FichePosteCreation\FichePosteCreationForm;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Service\Agent\AgentService;
use Application\Service\FichePoste\FichePosteService;
use Zend\Mvc\Controller\ControllerManager;

class FichePosteControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var FichePosteService $fichePosteService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $fichePosteService = $manager->getServiceLocator()->get(FichePosteService::class);

        /**
         * @var AjouterFicheMetierForm $ajouterFicheMetierForm
         * @var AssocierAgentForm $associerAgentForm
         * @var AssocierPosteForm $associerPosteForm
         * @var FichePosteCreationForm $fichePosteCreation
         * @var SpecificitePosteForm $specificiftePosteForm
         */
        $ajouterFicheMetierForm = $manager->getServiceLocator()->get('FormElementManager')->get(AjouterFicheMetierForm::class);
        $associerAgentForm = $manager->getServiceLocator()->get('FormElementManager')->get(AssocierAgentForm::class);
        $associerPosteForm = $manager->getServiceLocator()->get('FormElementManager')->get(AssocierPosteForm::class);
        $fichePosteCreation = $manager->getServiceLocator()->get('FormElementManager')->get(FichePosteCreationForm::class);
        $specificiftePosteForm = $manager->getServiceLocator()->get('FormElementManager')->get(SpecificitePosteForm::class);

        /** @var FichePosteController $controller */
        $controller = new FichePosteController();

        $controller->setAgentService($agentService);
        $controller->setFichePosteService($fichePosteService);

        $controller->setAjouterFicheTypeForm($ajouterFicheMetierForm);
        $controller->setAssocierAgentForm($associerAgentForm);
        $controller->setAssocierPosteForm($associerPosteForm);
        $controller->setFichePosteCreationForm($fichePosteCreation);
        $controller->setSpecificitePosteForm($specificiftePosteForm);
        return $controller;
    }
}