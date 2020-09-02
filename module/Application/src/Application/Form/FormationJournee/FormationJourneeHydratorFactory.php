<?php

namespace Application\Form\FormationJournee;

use Interop\Container\ContainerInterface;

class FormationJourneeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationJourneeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationJourneeHydrator $hydrator */
        $hydrator = new FormationJourneeHydrator();
        return $hydrator;
    }
}