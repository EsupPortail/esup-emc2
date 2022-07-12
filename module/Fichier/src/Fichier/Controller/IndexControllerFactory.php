<?php

namespace Fichier\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\ControllerManager;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var IndexController $controller */
        $controller = new IndexController();
        return $controller;
    }
}