<?php

namespace Formation\Form\SelectionFormation;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionFormationFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionFormationForm
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        /**
         * @var SelectionFormationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionFormationHydrator::class);

        $form = new SelectionFormationForm();
        $form->setHydrator($hydrator);
        $form->setFormationService($formationService);
        return $form;
    }
}