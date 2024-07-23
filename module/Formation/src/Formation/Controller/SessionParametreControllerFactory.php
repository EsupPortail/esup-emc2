<?php

namespace Formation\Controller;

use Formation\Form\SessionParametre\SessionParametreForm;
use Formation\Service\Session\SessionService;
use Formation\Service\SessionParametre\SessionParametreService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SessionParametreControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return SessionParametreController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SessionParametreController
    {
        /**
         * @var SessionService $sessionService
         * @var SessionParametreService $sessionParametreService
         * @var SessionParametreForm $sessionParametreForm
         */
        $sessionService = $container->get(SessionService::class);
        $sessionParametreService = $container->get(SessionParametreService::class);
        $sessionParametreForm = $container->get('FormElementManager')->get(SessionParametreForm::class);

        $controller = new SessionParametreController();
        $controller->setSessionService($sessionService);
        $controller->setSessionParametreService($sessionParametreService);
        $controller->setSessionParametreForm($sessionParametreForm);
        return $controller;
    }
}