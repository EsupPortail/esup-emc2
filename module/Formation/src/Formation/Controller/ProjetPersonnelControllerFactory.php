<?php

namespace Formation\Controller;

use Psr\Container\ContainerInterface;

class ProjetPersonnelControllerFactory {

    public function __invoke(ContainerInterface $container) : ProjetPersonnelController
    {
        $controller = new ProjetPersonnelController();
        return $controller;
    }
}