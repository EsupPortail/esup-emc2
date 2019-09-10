<?php

namespace Application\Form\FicheMetier;


use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class ApplicationsHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var ApplicationService $applicationService */
        $applicationService = $container->get(ApplicationService::class);

        $hydrator = new ApplicationsHydrator();
        $hydrator->setApplicationService($applicationService);

        return $hydrator;
    }
}