<?php

namespace Element\Form\CompetenceImportation;


use Psr\Container\ContainerInterface;

class CompetenceImportationHydratorFactory
{
    public function __invoke(ContainerInterface $container) : CompetenceImportationHydrator
    {
        return new CompetenceImportationHydrator();
    }


}

