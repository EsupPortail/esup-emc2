<?php

namespace Carriere\Controller;

use Agent\Service\AgentEmploiType\AgentEmploiTypeService;
use Application\Service\Util\UtilService;
use Carriere\Service\EmploiType\EmploiTypeService;
use Interop\Container\ContainerInterface;
use MyNamespace\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class EmploiTypeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return EmploiTypeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiTypeController
    {
        /**
         * @var AgentEmploiTypeService $agentEmploiTypeService
         * @var EmploiTypeService $emploiTypeService
         * @var ParametreService $parametreService
         * @var UserService $userService
         * @var UtilService $utilService
         */
        $emploiTypeService = $container->get(EmploiTypeService::class);
        $agentEmploiTypeService = $container->get(AgentEmploiTypeService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);
        $utilService = $container->get(UtilService::class);

        $controller = new EmploiTypeController();
        $controller->setAgentEmploiTypeService($agentEmploiTypeService);
        $controller->setEmploiTypeService($emploiTypeService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);
        $controller->setUtilService($utilService);
        return $controller;
    }
}