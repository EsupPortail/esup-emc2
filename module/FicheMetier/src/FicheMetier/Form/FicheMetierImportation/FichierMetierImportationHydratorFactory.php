<?php

namespace FicheMetier\Form\FicheMetierImportation;


use Psr\Container\ContainerInterface;

class FichierMetierImportationHydratorFactory
{
    public function __invoke(ContainerInterface $container) : FichierMetierImportationHydrator
    {
        return new FichierMetierImportationHydrator();
    }


}

