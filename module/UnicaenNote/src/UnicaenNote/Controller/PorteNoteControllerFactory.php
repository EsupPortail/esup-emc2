<?php

namespace UnicaenNote\Controller;

use Interop\Container\ContainerInterface;
use UnicaenNote\Service\PorteNote\PorteNoteService;

class PorteNoteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    public function __invoke(ContainerInterface  $container)
    {
        /**
         * @var PorteNoteService $porteNoteService
         */
        $porteNoteService = $container->get(PorteNoteService::class);

        $controller = new PorteNoteController();
        $controller->setPorteNoteService($porteNoteService);
        return $controller;
    }
}