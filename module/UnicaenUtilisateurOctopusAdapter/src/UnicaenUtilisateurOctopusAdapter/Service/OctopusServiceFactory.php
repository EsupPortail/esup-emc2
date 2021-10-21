<?php

namespace UnicaenUtilisateurOctopusAdapter\Service;

use Interop\Container\ContainerInterface;
use Octopus\Service\Individu\IndividuService;

class OctopusServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return OctopusService
     */
    public function __invoke(ContainerInterface $container) {

        /**
         * @var IndividuService $individuService
         */
        $individuService = $container->get(IndividuService::class);

        /** @var OctopusService $service */
        $service = new OctopusService();
        $service->setIndividuService($individuService);

        return $service;
    }
}