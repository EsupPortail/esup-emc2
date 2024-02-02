<?php

namespace Formation\Form\ActionCoutPrevisionnel;

use Formation\Service\Formation\FormationService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionCoutPrevisionnelFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActionCoutPrevisionnelForm
    {
        /**
         * @var FormationService $formationService
         * @var PlanDeFormationService $planService
         * @var ActionCoutPrevisionnelHydrator $hydrator
         */
        $formationService = $container->get(FormationService::class);
        $planService = $container->get(PlanDeFormationService::class);
        $hydrator = $container->get('HydratorManager')->get(ActionCoutPrevisionnelHydrator::class);

        $form = new ActionCoutPrevisionnelForm();
        $form->setFormationService($formationService);
        $form->setPlanDeFormationService($planService);
        $form->setHydrator($hydrator);
        return $form;
    }
}