<?php

namespace Carriere\Form\SpecialiteType;

use Psr\Container\ContainerInterface;

class SpecialiteTypeHydratorFactory
{
    public function __invoke(ContainerInterface $container): SpecialiteTypeHydrator
    {
        $hydratror = new SpecialiteTypeHydrator();
        return $hydratror;
    }
}
