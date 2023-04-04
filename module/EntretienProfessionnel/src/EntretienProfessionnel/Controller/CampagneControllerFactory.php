<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentService;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

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
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var NotificationService $notificationService
         * @var RappelCampagneAvancementService $rappelCampagneAvancementService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $notificationService = $container->get(NotificationService::class);
        $rappelCampagneAvancementService = $container->get(RappelCampagneAvancementService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var CampagneForm $campagneForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);

        $controller = new CampagneController();
        $controller->setAgentService($agentService);
        $controller->setCampagneService($campagneService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setNotificationService($notificationService);
        $controller->setRappelCampagneAvancementService($rappelCampagneAvancementService);
        $controller->setStructureService($structureService);
        $controller->setCampagneForm($campagneForm);
        return $controller;
    }
}