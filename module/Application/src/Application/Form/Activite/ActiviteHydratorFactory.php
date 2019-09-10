<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class ActiviteHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         * @var FormationService $formationService
         */
        $applicationService = $container->get(ApplicationService::class);
        $formationService = $container->get(FormationService::class);

        $hydrator = new ActiviteHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setFormationService($formationService);

        return $hydrator;
    }
}