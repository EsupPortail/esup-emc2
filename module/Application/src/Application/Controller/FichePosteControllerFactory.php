<?php

namespace Application\Controller;

use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\FichePosteCreation\FichePosteCreationForm;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Service\Agent\AgentService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;

class FichePosteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FichePosteService $fichePosteService
         */
        $agentService = $container->get(AgentService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var AjouterFicheMetierForm $ajouterFicheMetierForm
         * @var AssocierAgentForm $associerAgentForm
         * @var AssocierPosteForm $associerPosteForm
         * @var AssocierTitreForm $associerTitreForm
         * @var FichePosteCreationForm $fichePosteCreation
         * @var SpecificitePosteForm $specificiftePosteForm
         */
        $ajouterFicheMetierForm = $container->get('FormElementManager')->get(AjouterFicheMetierForm::class);
        $associerAgentForm = $container->get('FormElementManager')->get(AssocierAgentForm::class);
        $associerPosteForm = $container->get('FormElementManager')->get(AssocierPosteForm::class);
        $associerTitreForm = $container->get('FormElementManager')->get(AssocierTitreForm::class);
        $fichePosteCreation = $container->get('FormElementManager')->get(FichePosteCreationForm::class);
        $specificiftePosteForm = $container->get('FormElementManager')->get(SpecificitePosteForm::class);

        /** @var FichePosteController $controller */
        $controller = new FichePosteController();

        $controller->setAgentService($agentService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setStructureService($structureService);

        $controller->setAjouterFicheTypeForm($ajouterFicheMetierForm);
        $controller->setAssocierAgentForm($associerAgentForm);
        $controller->setAssocierPosteForm($associerPosteForm);
        $controller->setAssocierTitreForm($associerTitreForm);
        $controller->setFichePosteCreationForm($fichePosteCreation);
        $controller->setSpecificitePosteForm($specificiftePosteForm);
        return $controller;
    }
}