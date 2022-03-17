<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class SelectionNiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
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