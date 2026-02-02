<?php

namespace FicheMetier\Form\ThematiqueElement;

use Element\Service\NiveauMaitrise\NiveauMaitriseService;
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
         * @var NiveauMaitriseService $niveauService
         */
        $niveauMaitriseService = $container->get(NiveauMaitriseService::class);

        $hydrator = new ThematiqueElementHydrator();
        $hydrator->setNiveauMaitriseService($niveauMaitriseService);
        return $hydrator;
    }
}