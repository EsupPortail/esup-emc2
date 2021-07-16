<?php

namespace Application\Form\Niveau;

use Interop\Container\ContainerInterface;

class NiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = $container->get('HydratorManager')->get(NiveauHydrator::class);
        return $hydrator;
    }
}