<?php

namespace Application\Form\Formation;

use Application\Service\Formation\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationThemeService $formationThemeService */
        $formationThemeService = $container->get(FormationThemeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationHydrator::class);

        /** @var FormationForm $form */
        $form = new FormationForm();
        $form->setFormationThemeService($formationThemeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}