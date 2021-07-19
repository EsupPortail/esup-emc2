<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Service\Activite\ActiviteService;
use Application\Service\SpecificiteActivite\SpecificiteActiviteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Interop\Container\ContainerInterface;

class SpecificiteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return SpecificiteController
     */
    public function __invoke(ContainerInterface $container) : SpecificiteController
    {
        /**
         * @var ActiviteService $activiteService
         * @var SpecificiteActiviteService $specificiteActiviteService
         * @var SpecificitePosteService $specificitePosteService
         */
        $activiteService = $container->get(ActiviteService::class);
        $specificiteActiviteService = $container->get(SpecificiteActiviteService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);

        $controller = new SpecificiteController();
        $controller->setActiviteService($activiteService);
        $controller->setSpecificiteActiviteService($specificiteActiviteService);
        $controller->setSpecificitePosteService($specificitePosteService);
        return $controller;
    }

}