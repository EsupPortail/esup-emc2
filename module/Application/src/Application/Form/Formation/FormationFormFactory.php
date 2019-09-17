<?php

namespace Application\Form\Formation;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationHydrator::class);

        /** @var FormationForm $form */
        $form = new FormationForm();
        $form->setFormationService($formationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}