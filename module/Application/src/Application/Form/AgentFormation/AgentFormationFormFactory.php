<?php

namespace Application\Form\AgentFormation;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class AgentFormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var AgentFormationHydrator $hydrator
         */
        $formationService = $container->get(FormationService::class);
        $hydrator = $container->get('HydratorManager')->get(AgentFormationHydrator::class);

        /** @var AgentFormationForm $form */
        $form = new AgentFormationForm();
        $form->setFormationService($formationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}