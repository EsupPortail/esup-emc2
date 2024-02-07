<?php

namespace Formation\Form\EnqueteReponse;

use Interop\Container\ContainerInterface;

class EnqueteReponseHydratorFactory
{
    public function __invoke(ContainerInterface $container): EnqueteReponseHydrator
    {
        $hydrator = new EnqueteReponseHydrator();
        return $hydrator;
    }
}