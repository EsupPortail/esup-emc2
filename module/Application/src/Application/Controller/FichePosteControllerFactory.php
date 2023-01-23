<?php

namespace Application\Controller;

use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\Expertise\ExpertiseForm;
use Application\Form\Poste\PosteForm;
use Application\Form\Rifseep\RifseepForm;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesService;
use Application\Service\Agent\AgentService;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesService;
use Application\Service\CompetencesRetirees\CompetencesRetireesService;
use Application\Service\Expertise\ExpertiseService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\Notification\NotificationService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Application\Service\Poste\PosteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class FichePosteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FichePosteController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FichePosteController
    {
        /**
         * @var AgentService $agentService
         * @var RenduService $renduService
         * @var FicheMetierService $ficheMetierService
         * @var FichePosteService $fichePosteService
         * @var ActiviteService $activiteService
         * @var ActivitesDescriptionsRetireesService $activitesDescriptionsRetireesSercice
         * @var ApplicationsRetireesService $applicationsConserveesService
         * @var CompetencesRetireesService $competencesRetireesService
         * @var EtatService $etatService
         * @var ExpertiseService $expertiseService
         * @var NotificationService $notificationService
         * @var ParcoursDeFormationService $parcoursService
         * @var PosteService $posteService
         * @var SpecificitePosteService $specificitePosteService
         * @var ValidationInstanceService $validationInstanceService
         */
        $agentService = $container->get(AgentService::class);
        $renduService = $container->get(RenduService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);
        $activiteService = $container->get(ActiviteService::class);
        $activitesDescriptionsRetireesSercice = $container->get(ActivitesDescriptionsRetireesService::class);
        $applicationsConserveesService = $container->get(ApplicationsRetireesService::class);
        $competencesRetireesService = $container->get(CompetencesRetireesService::class);
        $etatService = $container->get(EtatService::class);
        $expertiseService = $container->get(ExpertiseService::class);
        $notificationService = $container->get(NotificationService::class);
        $posteService = $container->get(PosteService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);
        $parcoursService = $container->get(ParcoursDeFormationService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var AjouterFicheMetierForm $ajouterFicheMetierForm
         * @var AssocierTitreForm $associerTitreForm
         * @var ExpertiseForm $expertiseForm
         * @var PosteForm $posteForm
         * @var RifseepForm $rifseepForm
         * @var SelectionEtatForm $selectionEtatForm
         * @var SpecificitePosteForm $specificiftePosteForm
         */
        $ajouterFicheMetierForm = $container->get('FormElementManager')->get(AjouterFicheMetierForm::class);
        $associerTitreForm = $container->get('FormElementManager')->get(AssocierTitreForm::class);
        $expertiseForm = $container->get('FormElementManager')->get(ExpertiseForm::class);
        $posteForm = $container->get('FormElementManager')->get(PosteForm::class);
        $rifseepForm = $container->get('FormElementManager')->get(RifseepForm::class);
        $selectionEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $specificiftePosteForm = $container->get('FormElementManager')->get(SpecificitePosteForm::class);



        /** @var FichePosteController $controller */
        $controller = new FichePosteController();

        $controller->setAgentService($agentService);
        $controller->setRenduService($renduService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setStructureService($structureService);
        $controller->setActiviteService($activiteService);
        $controller->setActivitesDescriptionsRetireesService($activitesDescriptionsRetireesSercice);
        $controller->setApplicationsRetireesService($applicationsConserveesService);
        $controller->setCompetencesRetireesService($competencesRetireesService);
        $controller->setEtatService($etatService);
        $controller->setExpertiseService($expertiseService);
        $controller->setNotificationService($notificationService);
        $controller->setPosteService($posteService);
        $controller->setSpecificitePosteService($specificitePosteService);
        $controller->setParcoursDeFormationService($parcoursService);
        $controller->setValidationInstanceService($validationInstanceService);

        $controller->setAjouterFicheTypeForm($ajouterFicheMetierForm);
        $controller->setAssocierTitreForm($associerTitreForm);
        $controller->setExpertiseForm($expertiseForm);
        $controller->setPosteForm($posteForm);
        $controller->setRifseepForm($rifseepForm);
        $controller->setSelectionEtatForm($selectionEtatForm);
        $controller->setSpecificitePosteForm($specificiftePosteForm);

        return $controller;
    }
}