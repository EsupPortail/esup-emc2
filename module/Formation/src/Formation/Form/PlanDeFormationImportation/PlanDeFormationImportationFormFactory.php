<?php

namespace Formation\Form\PlanDeFormationImportation;

use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PlanDeFormationImportationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return PlanDeFormationImportationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PlanDeFormationImportationForm
    {
        /**
         * @var PlanDeFormationService $planDeFormationservice
         * @var PlanDeFormationImportationHydrator $hydrator
         */
        $planDeFormationservice = $container->get(PlanDeFormationService::class);
        $hydrator = $container->get('HydratorManager')->get(PlanDeFormationImportationHydrator::class);

        $form = new PlanDeFormationImportationForm();
        $form->setPlanDeFormationService($planDeFormationservice);
        $form->setHydrator($hydrator);
        return $form;
    }
}