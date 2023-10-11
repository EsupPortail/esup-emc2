<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\FichePoste\FichePosteService;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class EntretienProfessionnelControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EntretienProfessionnelController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var RenduService $renduService
         * @var UserService $userService
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var EtatInstanceService $etatInstanceService
         * @var EtatTypeService $etatTypeService
         * @var FichePosteService $fichePosteService
         * @var MailService $mailService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var RappelEntretienProfessionnelService $rappelEntretienProfessionnelService
         * @var RappelPasObservationService $rappelPasObservationService
         * @var StructureService $structureService
         * @var ValidationInstanceService $validationInstanceService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $renduService = $container->get(RenduService::class);
        $userService = $container->get(UserService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $fichePosteService = $container->get(FichePosteService::class);

        $entretienProfesionnelService = $container->get(EntretienProfessionnelService::class);
        $campagneService = $container->get(CampagneService::class);

        $mailService = $container->get(MailService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $rappelEntretienProfessionnelService = $container->get(RappelEntretienProfessionnelService::class);
        $rappelPasObservationService = $container->get(RappelPasObservationService::class);
        $structureService = $container->get(StructureService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var EntretienProfessionnelForm $entretienProfessionnelForm
         */
        $entretienProfessionnelForm = $container->get('FormElementManager')->get(EntretienProfessionnelForm::class);

        /** @var EntretienProfessionnelController $controller */
        $controller = new EntretienProfessionnelController();

        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setRenduService($renduService);
        $controller->setUserService($userService);
        $controller->setEntretienProfessionnelService($entretienProfesionnelService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setCampagneService($campagneService);
        $controller->setParametreService($parametreService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setMailService($mailService);
        $controller->setNotificationService($notificationService);
        $controller->setRappelEntretienProfessionnelService($rappelEntretienProfessionnelService);
        $controller->setRappelPasObservationService($rappelPasObservationService);
        $controller->setStructureService($structureService);

        $controller->setEntretienProfessionnelForm($entretienProfessionnelForm);

        return $controller;
    }
}
