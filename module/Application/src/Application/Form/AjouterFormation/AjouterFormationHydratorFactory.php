<?php

namespace Application\Form\AjouterFormation;

use Interop\Container\ContainerInterface;

class AjouterFormationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AjouterFormationHydrator $hydrator */
        $hydrator = new AjouterFormationHydrator();
        return $hydrator;
    }
}