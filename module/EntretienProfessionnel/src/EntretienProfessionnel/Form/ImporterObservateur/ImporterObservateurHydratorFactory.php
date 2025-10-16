<?php

namespace EntretienProfessionnel\Form\ImporterObservateur;


use Psr\Container\ContainerInterface;

class ImporterObservateurHydratorFactory
{
    public function __invoke(ContainerInterface $container) : ImporterObservateurHydrator
    {
        return new ImporterObservateurHydrator();
    }


}

