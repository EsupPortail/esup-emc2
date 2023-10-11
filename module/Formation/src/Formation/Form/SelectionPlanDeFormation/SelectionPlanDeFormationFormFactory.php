<?php

namespace Formation\Form\SelectionPlanDeFormation;

use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionPlanDeFormationFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionPlanDeFormationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionPlanDeFormationForm
    {
        /**
         * @var PlanDeFormationService $planService
         */
        $planService = $container->get(PlanDeFormationService::class);

        /**
         * @var SelectionPlanDeFormationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionPlanDeFormationHydrator::class);

        $form = new SelectionPlanDeFormationForm();
        $form->setHydrator($hydrator);
        $form->setPlanDeFormationService($planService);
        return $form;
    }
}