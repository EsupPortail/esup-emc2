<?php

namespace Application\Form\CompetenceMaitrise;

use Interop\Container\ContainerInterface;

class CompetenceMaitriseHydratorFactory {


    /**
     * @param ContainerInterface $container
     * @return CompetenceMaitriseHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new CompetenceMaitriseHydrator();
        return $hydrator;
    }
}