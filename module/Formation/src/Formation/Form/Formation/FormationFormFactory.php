<?php

namespace Formation\Form\Formation;

use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationTheme\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationGroupeService $formationGroupeService
         * @var FormationThemeService $formationThemeService
         */
        $formationThemeService = $container->get(FormationThemeService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationHydrator::class);

        /** @var FormationForm $form */
        $form = new FormationForm();
        $form->setFormationGroupeService($formationGroupeService);
        $form->setFormationThemeService($formationThemeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}