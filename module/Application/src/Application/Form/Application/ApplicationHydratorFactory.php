<?php 

namespace Application\Form\Application;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class ApplicationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = new ApplicationHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}