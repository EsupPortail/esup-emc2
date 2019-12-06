<?php

namespace Application\Controller;

use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\FichePosteCreation\FichePosteCreationForm;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Service\Agent\AgentService;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesService;
use Application\Service\CompetencesRetirees\CompetencesRetireesService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FormationsRetirees\FormationsRetireesService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class FichePosteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FicheMetierService $ficheMetierService
         * @var FichePosteService $fichePosteService
         * @var ApplicationsRetireesService $applicationsConserveesService
         * @var CompetencesRetireesService $competencesRetireesService
         * @var FormationsRetireesService $formationsConserseesService
         */
        $agentService = $container->get(AgentService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);
        $applicationsConserveesService = $container->get(ApplicationsRetireesService::class);
        $competencesRetireesService = $container->get(CompetencesRetireesService::class);
        $formationsConserseesService = $container->get(FormationsRetireesService::class);

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

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FichePosteController $controller */
        $controller = new FichePosteController();
        $controller->setRenderer($renderer);

        $controller->setAgentService($agentService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setStructureService($structureService);
        $controller->setApplicationsRetireesService($applicationsConserveesService);
        $controller->setCompetencesRetireesService($competencesRetireesService);
        $controller->setFormationsRetireesService($formationsConserseesService);

        $controller->setAjouterFicheTypeForm($ajouterFicheMetierForm);
        $controller->setAssocierAgentForm($associerAgentForm);
        $controller->setAssocierPosteForm($associerPosteForm);
        $controller->setAssocierTitreForm($associerTitreForm);
        $controller->setFichePosteCreationForm($fichePosteCreation);
        $controller->setSpecificitePosteForm($specificiftePosteForm);
        return $controller;
    }
}