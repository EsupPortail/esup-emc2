<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Formation\Service\Presence\PresenceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PresenceControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return PresenceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PresenceController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         * @var PresenceService $presenceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
        $presenceService = $container->get(PresenceService::class);

        $controller = new PresenceController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setPresenceService($presenceService);
        return $controller;
    }
}