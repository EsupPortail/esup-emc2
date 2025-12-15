<?php

namespace Carriere\Form\SelectionnerNiveauCarriere;

use Carriere\Service\Niveau\NiveauService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerNiveauCarriereHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerNiveauCarriereHydrator
    {
        /** @var NiveauService $niveauService */
        $niveauService = $container->get(NiveauService::class);

        $hydrator = new SelectionnerNiveauCarriereHydrator();
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}
