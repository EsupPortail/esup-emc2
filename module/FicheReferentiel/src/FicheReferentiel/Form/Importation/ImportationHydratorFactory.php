<?php

namespace FicheReferentiel\Form\Importation;


use Psr\Container\ContainerInterface;

class ImportationHydratorFactory
{
    public function __invoke(ContainerInterface $container) : ImportationHydrator
    {
        return new ImportationHydrator();
    }


}

