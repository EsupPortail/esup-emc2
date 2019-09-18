<?php

namespace Application\Form\FicheMetier;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationsHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var FormationsHydrator $hydrator */
        $hydrator = new FormationsHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}