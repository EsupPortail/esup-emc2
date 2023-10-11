<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\Niveau\NiveauService;
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
         * @var NiveauService $niveauService
         */
        $niveauService = $container->get(NiveauService::class);

        $hydrator = new SelectionNiveauHydrator();
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}