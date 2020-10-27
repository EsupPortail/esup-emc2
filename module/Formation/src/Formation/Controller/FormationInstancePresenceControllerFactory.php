<?php

namespace Formation\Controller;

use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Application\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstancePresence\FormationInstancePresenceService;
use Interop\Container\ContainerInterface;

class FormationInstancePresenceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstancePresenceController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         * @var FormationInstancePresenceService $foramtionInstancePresenceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
        $foramtionInstancePresenceService = $container->get(FormationInstancePresenceService::class);

        $controller = new FormationInstancePresenceController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setFormationInstancePresenceService($foramtionInstancePresenceService);
        return $controller;
    }
}