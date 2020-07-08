<?php

namespace Application\Controller;

use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\Expertise\ExpertiseForm;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesService;
use Application\Service\Agent\AgentService;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesService;
use Application\Service\CompetencesRetirees\CompetencesRetireesService;
use Application\Service\Expertise\ExpertiseService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FormationsRetirees\FormationsRetireesService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
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
         * @var ActiviteService $activiteService
         * @var ActivitesDescriptionsRetireesService $activitesDescriptionsRetireesSercice
         * @var ApplicationsRetireesService $applicationsConserveesService
         * @var CompetencesRetireesService $competencesRetireesService
         * @var FormationsRetireesService $formationsConserseesService
         * @var ExpertiseService $expertiseService
         * @var SpecificitePosteService $specificitePosteService
         * @var ParcoursDeFormationService $parcoursService
         */
        $agentService = $container->get(AgentService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);
        $activiteService = $container->get(ActiviteService::class);
        $activitesDescriptionsRetireesSercice = $container->get(ActivitesDescriptionsRetireesService::class);
        $applicationsConserveesService = $container->get(ApplicationsRetireesService::class);
        $competencesRetireesService = $container->get(CompetencesRetireesService::class);
        $formationsConserseesService = $container->get(FormationsRetireesService::class);
        $expertiseService = $container->get(ExpertiseService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /**
         * @var AjouterFicheMetierForm $ajouterFicheMetierForm
         * @var AssocierAgentForm $associerAgentForm
         * @var AssocierPosteForm $associerPosteForm
         * @var AssocierTitreForm $associerTitreForm
         * @var SpecificitePosteForm $specificiftePosteForm
         * @var ExpertiseForm $expertiseForm
         */
        $ajouterFicheMetierForm = $container->get('FormElementManager')->get(AjouterFicheMetierForm::class);
        $associerAgentForm = $container->get('FormElementManager')->get(AssocierAgentForm::class);
        $associerPosteForm = $container->get('FormElementManager')->get(AssocierPosteForm::class);
        $associerTitreForm = $container->get('FormElementManager')->get(AssocierTitreForm::class);
        $specificiftePosteForm = $container->get('FormElementManager')->get(SpecificitePosteForm::class);
        $expertiseForm = $container->get('FormElementManager')->get(ExpertiseForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FichePosteController $controller */
        $controller = new FichePosteController();
        $controller->setRenderer($renderer);

        $controller->setAgentService($agentService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setStructureService($structureService);
        $controller->setActiviteService($activiteService);
        $controller->setActivitesDescriptionsRetireesService($activitesDescriptionsRetireesSercice);
        $controller->setApplicationsRetireesService($applicationsConserveesService);
        $controller->setCompetencesRetireesService($competencesRetireesService);
        $controller->setFormationsRetireesService($formationsConserseesService);
        $controller->setExpertiseService($expertiseService);
        $controller->setSpecificitePosteService($specificitePosteService);
        $controller->setParcoursDeFormationService($parcoursService);

        $controller->setAjouterFicheTypeForm($ajouterFicheMetierForm);
        $controller->setAssocierAgentForm($associerAgentForm);
        $controller->setAssocierPosteForm($associerPosteForm);
        $controller->setAssocierTitreForm($associerTitreForm);
        $controller->setSpecificitePosteForm($specificiftePosteForm);
        $controller->setExpertiseForm($expertiseForm);

        return $controller;
    }
}