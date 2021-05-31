<?php

namespace Formation\Form\Formation;

use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;

class FormationHydratorFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationGroupeService $formationGroupeService
         */
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        $hydrator->setFormationGroupeService($formationGroupeService);
        return $hydrator;
    }
}