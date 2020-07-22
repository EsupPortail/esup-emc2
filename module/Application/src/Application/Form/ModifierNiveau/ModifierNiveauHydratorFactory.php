<?php

namespace Application\Form\ModifierNiveau;

use Interop\Container\ContainerInterface;

class ModifierNiveauHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModifierNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ModifierNiveauHydrator $hydrator */
        $hydrator = new ModifierNiveauHydrator();
        return $hydrator;
    }
}