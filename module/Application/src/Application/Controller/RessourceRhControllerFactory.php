<?php

namespace Application\Controller;

use Application\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;

class RessourceRhControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return RessourceRhController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierService $metierService
         */
        $metierService       = $container->get(MetierService::class);


        /** @var RessourceRhController $controller */
        $controller = new RessourceRhController();
        $controller->setMetierService($metierService);

        return $controller;
    }

}