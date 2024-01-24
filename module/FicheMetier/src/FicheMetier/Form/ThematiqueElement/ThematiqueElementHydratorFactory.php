<?php

namespace FicheMetier\Form\ThematiqueElement;

use Element\Service\Niveau\NiveauService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ThematiqueElementHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ThematiqueElementHydrator
    {
        /**
         * @var NiveauService $niveauService
         */
        $niveauService = $container->get(NiveauService::class);

        $hydrator = new ThematiqueElementHydrator();
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}