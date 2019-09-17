<?php

namespace Application\Form\Formation;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}