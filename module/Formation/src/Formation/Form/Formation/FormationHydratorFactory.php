<?php

namespace Formation\Form\Formation;

use Formation\Form\Formateur\FormateurHydrator;
use Formation\Service\ActionType\ActionTypeService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationHydrator
    {
        /**
         * @var ActionTypeService $actionTypeService
         * @var FormationGroupeService $formationGroupeService
         */
        $actionTypeService = $container->get(ActionTypeService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        $hydrator->setActionTypeService($actionTypeService);
        $hydrator->setFormationGroupeService($formationGroupeService);
        return $hydrator;
    }
}