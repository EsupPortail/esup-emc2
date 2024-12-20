<?php

namespace Application\Controller;

use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\Rifseep\RifseepForm;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesService;
use Application\Service\Agent\AgentService;
use Application\Service\AgentPoste\AgentPosteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesService;
use Application\Service\CompetencesRetirees\CompetencesRetireesService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FichePoste\Form\Expertise\ExpertiseForm;
use FichePoste\Service\Expertise\ExpertiseService;
use FichePoste\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class FichePosteControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FichePosteController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FichePosteController
    {
        /**
         * @var AgentService $agentService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentPosteService $agentPosteService
         * @var RenduService $renduService
         * @var FicheMetierService $ficheMetierService
         * @var FichePosteService $fichePosteService
         * @var ApplicationsRetireesService $applicationsConserveesService
         * @var CompetencesRetireesService $competencesRetireesService
         * @var EtatInstanceService $etatInstanceService
         * @var ExpertiseService $expertiseService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var SpecificitePosteService $specificitePosteService
         * @var ValidationInstanceService $validationInstanceService
         */
        $agentService = $container->get(AgentService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $agentPosteService = $container->get(AgentPosteService::class);
        $renduService = $container->get(RenduService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);
        $applicationsConserveesService = $container->get(ApplicationsRetireesService::class);
        $competencesRetireesService = $container->get(CompetencesRetireesService::class);
        $activitesDescriptionsRetireesService = $container->get(ActivitesDescriptionsRetireesService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $expertiseService = $container->get(ExpertiseService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var AjouterFicheMetierForm $ajouterFicheMetierForm
         * @var AssocierTitreForm $associerTitreForm
         * @var CodeFonctionForm $codeFonctionForm
         * @var ExpertiseForm $expertiseForm
         * @var RifseepForm $rifseepForm
         * @var SelectionEtatForm $selectionEtatForm
         * @var SpecificitePosteForm $specificiftePosteForm
         */
        $ajouterFicheMetierForm = $container->get('FormElementManager')->get(AjouterFicheMetierForm::class);
        $associerTitreForm = $container->get('FormElementManager')->get(AssocierTitreForm::class);
        $codeFonctionForm = $container->get('FormElementManager')->get(CodeFonctionForm::class);
        $expertiseForm = $container->get('FormElementManager')->get(ExpertiseForm::class);
        $rifseepForm = $container->get('FormElementManager')->get(RifseepForm::class);
        $selectionEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $specificiftePosteForm = $container->get('FormElementManager')->get(SpecificitePosteForm::class);


        $controller = new FichePosteController();

        $controller->setActivitesDescriptionsRetireesService($activitesDescriptionsRetireesService);
        $controller->setAgentService($agentService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentPosteService($agentPosteService);
        $controller->setRenduService($renduService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setStructureService($structureService);
        $controller->setApplicationsRetireesService($applicationsConserveesService);
        $controller->setCompetencesRetireesService($competencesRetireesService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setExpertiseService($expertiseService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setSpecificitePosteService($specificitePosteService);
        $controller->setValidationInstanceService($validationInstanceService);

        $controller->setAjouterFicheTypeForm($ajouterFicheMetierForm);
        $controller->setAssocierTitreForm($associerTitreForm);
        $controller->setCodeFonctionForm($codeFonctionForm);
        $controller->setExpertiseForm($expertiseForm);
        $controller->setRifseepForm($rifseepForm);
        $controller->setSelectionEtatForm($selectionEtatForm);
        $controller->setSpecificitePosteForm($specificiftePosteForm);

        return $controller;
    }
}