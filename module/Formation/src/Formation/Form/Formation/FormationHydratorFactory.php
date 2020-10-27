<?php

namespace Formation\Form\Formation;

use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationTheme\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationGroupeService $formationGroupeService
         * @var FormationThemeService $formationThemeService
         */
        $formationThemeService = $container->get(FormationThemeService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        $hydrator->setFormationThemeService($formationThemeService);
        $hydrator->setFormationGroupeService($formationGroupeService);
        return $hydrator;
    }
}