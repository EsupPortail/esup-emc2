<?php

namespace Application\Form\ParcoursDeFormation;

use Interop\Container\ContainerInterface;

class ParcoursDeFormationHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ParcoursDeFormationHydrator $hydrator */
        $hydrator = new ParcoursDeFormationHydrator();
        return $hydrator;
    }
}