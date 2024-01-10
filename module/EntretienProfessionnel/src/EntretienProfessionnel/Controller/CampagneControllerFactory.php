<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class CampagneControllerFactory extends AbstractActionController
{
    /**
     * @param ContainerInterface $container
     * @return CampagneController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CampagneController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var RappelCampagneAvancementAutoriteService $rappelCampagneAvancementAutoriteService
         * @var RappelCampagneAvancementSuperieurService $rappelCampagneAvancementSuperieurService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $rappelCampagneAvancementAutoriteService = $container->get(RappelCampagneAvancementAutoriteService::class);
        $rappelCampagneAvancementSuperieurService = $container->get(RappelCampagneAvancementSuperieurService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var CampagneForm $campagneForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);

        $controller = new CampagneController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setCampagneService($campagneService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setRappelCampagneAvancementAutoriteService($rappelCampagneAvancementAutoriteService);
        $controller->setRappelCampagneAvancementSuperieurService($rappelCampagneAvancementSuperieurService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);
        $controller->setCampagneForm($campagneForm);
        return $controller;
    }
}