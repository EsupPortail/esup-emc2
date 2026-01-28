<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionNiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionNiveauHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionNiveauHydrator
    {
        /**
         * @var NiveauMaitriseService $niveauService
         */
        $niveauService = $container->get(NiveauMaitriseService::class);

        $hydrator = new SelectionNiveauHydrator();
        $hydrator->setNiveauMaitriseService($niveauService);
        return $hydrator;
    }
}