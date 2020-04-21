<?php

namespace Application\Controller;

use Application\Service\Synchro\SynchroService;
use Application\Service\Synchro\SynchroServiceAwareTrait;
use Interop\Container\ContainerInterface;

class SynchroControllerFactory {
    use SynchroServiceAwareTrait;

    /**
     * @param ContainerInterface $container
     * @return SynchroController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var SynchroService $synchroService
         */
        $synchroService = $container->get(SynchroService::class);

        /** @var SynchroController $controller */
        $controller = new SynchroController();
        $controller->setSynchroService($synchroService);
        return $controller;
    }

}