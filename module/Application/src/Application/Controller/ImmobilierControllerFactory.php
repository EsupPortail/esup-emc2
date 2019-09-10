<?php

namespace Application\Controller;

use Application\Service\Immobilier\ImmobilierService;
use Interop\Container\ContainerInterface;

class ImmobilierControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ImmobilierService $immobilierService
         */
        $immobilierService = $container->get(ImmobilierService::class);

        /** @var ImmobilierController $controller */
        $controller = new ImmobilierController();
        $controller->setImmobilierService($immobilierService);
        return $controller;
    }

}