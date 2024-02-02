<?php

namespace Formation\Form\ActionCoutPrevisionnel;

use Formation\Service\Formation\FormationService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionCoutPrevisionnelHydratorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActionCoutPrevisionnelHydrator
    {
        /**
         * @var FormationService $formationService
         * @var PlanDeFormationService $planService
         */
        $formationService = $container->get(FormationService::class);
        $planService = $container->get(PlanDeFormationService::class);

        $hydrator = new ActionCoutPrevisionnelHydrator();
        $hydrator->setFormationService($formationService);
        $hydrator->setPlanDeFormationService($planService);
        return $hydrator;
    }
}