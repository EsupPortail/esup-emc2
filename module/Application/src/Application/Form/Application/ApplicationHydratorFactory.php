<?php 

namespace Application\Form\Application;

use Application\Service\Application\ApplicationGroupeService;
use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class ApplicationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationGroupeService $applicationGroupeService
         * @var FormationService $formationService
         */
        $applicationGroupeService = $container->get(ApplicationGroupeService::class);
        $formationService = $container->get(FormationService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = new ApplicationHydrator();
        $hydrator->setApplicationGroupeService($applicationGroupeService);
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}