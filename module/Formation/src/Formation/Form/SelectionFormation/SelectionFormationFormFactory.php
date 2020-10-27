<?php

namespace Formation\Form\SelectionFormation;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class SelectionFormationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionFormationForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        /**
         * @var SelectionFormationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionFormationHydrator::class);

        /** @var SelectionFormationForm $form */
        $form = new SelectionFormationForm();
        $form->setHydrator($hydrator);
        $form->setFormationService($formationService);
        return $form;
    }
}