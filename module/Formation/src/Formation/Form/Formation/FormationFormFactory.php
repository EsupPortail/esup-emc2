<?php

namespace Formation\Form\Formation;

use Formation\Service\ActionType\ActionTypeService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationForm
    {
        /**
         * @var ActionTypeService $actionTypeService
         * @var FormationGroupeService $formationGroupeService
         */
        $actionTypeService = $container->get(ActionTypeService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationHydrator::class);

        $form = new FormationForm();
        $form->setActionTypeService($actionTypeService);
        $form->setFormationGroupeService($formationGroupeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}