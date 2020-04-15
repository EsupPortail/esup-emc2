<?php

namespace Application\Form\Formation;

use Application\Service\Formation\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationThemeService $formationThemeService */
        $formationThemeService = $container->get(FormationThemeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        $hydrator->setFormationThemeService($formationThemeService);
        return $hydrator;
    }
}