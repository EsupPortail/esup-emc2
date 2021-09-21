<?php

namespace UnicaenDocument\Form\Contenu;

use Interop\Container\ContainerInterface;

class ContenuHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ContenuHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new ContenuHydrator();
        return $hydrator;
    }

}