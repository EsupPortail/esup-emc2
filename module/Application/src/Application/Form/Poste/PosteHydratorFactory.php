<?php

namespace Application\Form\Poste;

use Interop\Container\ContainerInterface;

class PosteHydratorFactory {

    public function __invoke(ContainerInterface $container) : PosteHydrator
    {
        $hydrator = new PosteHydrator();

        return $hydrator;
    }
}
