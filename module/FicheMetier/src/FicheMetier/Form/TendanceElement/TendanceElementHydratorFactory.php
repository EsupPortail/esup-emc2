<?php

namespace FicheMetier\Form\TendanceElement;

use Element\Service\Niveau\NiveauService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TendanceElementHydratorFactory
{

    public function __invoke(ContainerInterface $container): TendanceElementHydrator
    {
        $hydrator = new TendanceElementHydrator();
        return $hydrator;
    }
}