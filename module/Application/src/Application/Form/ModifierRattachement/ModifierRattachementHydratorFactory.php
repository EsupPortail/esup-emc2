<?php

namespace Application\Form\ModifierRattachement;

use Interop\Container\ContainerInterface;

class ModifierRattachementHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModifierRattachementHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ModifierRattachementHydrator $hydrator */
        $hydrator = new ModifierRattachementHydrator();
        return $hydrator;
    }
}