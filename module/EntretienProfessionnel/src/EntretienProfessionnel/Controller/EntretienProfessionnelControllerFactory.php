<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use EntretienProfessionnel\Service\Observation\ObservationService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;
use Zend\View\Renderer\PhpRenderer;

class EntretienProfessionnelControllerFactory {

    public function __invoke(ContainerInterface $container) : EntretienProfessionnelController
    {
        /**
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var RenduService $renduService
         * @var UserService $userService
         * @var ConfigurationService $configurationService
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         * @var FichePosteService $fichePosteService
         * @var ObservationService $observationService
         * @var MailService $mailService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var ParcoursDeFormationService $parcoursDeFormationService
         * @var RappelEntretienProfessionnelService $rappelEntretienProfessionnelService
         * @var RappelPasObservationService $rappelPasObservationService
         * @var StructureService $structureService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $agentService = $container->get(AgentService::class);
        $renduService = $container->get(RenduService::class);
        $userService = $container->get(UserService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $fichePosteService = $container->get(FichePosteService::class);

        $entretienProfesionnelService = $container->get(EntretienProfessionnelService::class);
        $campagneService = $container->get(CampagneService::class);
        $observationService = $container->get(ObservationService::class);

        $mailService = $container->get(MailService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $parcoursDeFormationService = $container->get(ParcoursDeFormationService::class);
        $rappelEntretienProfessionnelService = $container->get(RappelEntretienProfessionnelService::class);
        $rappelPasObservationService = $container->get(RappelPasObservationService::class);
        $structureService = $container->get(StructureService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        /**
         * @var EntretienProfessionnelForm $entretienProfessionnelForm
         * @var CampagneForm $campagneForm
         * @var ObservationForm $observationForm
         */
        $entretienProfessionnelForm = $container->get('FormElementManager')->get(EntretienProfessionnelForm::class);
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);
        $observationForm = $container->get('FormElementManager')->get(ObservationForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var EntretienProfessionnelController $controller */
        $controller = new EntretienProfessionnelController();
        $controller->setRenderer($renderer);

        $controller->setAgentService($agentService);
        $controller->setRenduService($renduService);
        $controller->setUserService($userService);
        $controller->setConfigurationService($configurationService);
        $controller->setEntretienProfessionnelService($entretienProfesionnelService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setCampagneService($campagneService);
        $controller->setObservationService($observationService);
        $controller->setParametreService($parametreService);
        $controller->setParcoursDeFormationService($parcoursDeFormationService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setMailService($mailService);
        $controller->setNotificationService($notificationService);
        $controller->setRappelEntretienProfessionnelService($rappelEntretienProfessionnelService);
        $controller->setRappelPasObservationService($rappelPasObservationService);
        $controller->setStructureService($structureService);

        $controller->setEntretienProfessionnelForm($entretienProfessionnelForm);
        $controller->setCampagneForm($campagneForm);
        $controller->setObservationForm($observationForm);

        return $controller;
    }
}
