<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeService;
use Application\Service\Util\UtilService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class CorrespondanceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CorrespondanceController
    {
        /**
         * @var AgentGradeService $agentGradeService
         * @var CorrespondanceService $correspondanceService
         * @var ParametreService $parametreService
         * @var UserService $userService
         * @var UtilService $utilService
         */
        $agentGradeService = $container->get(AgentGradeService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);
        $utilService = $container->get(UtilService::class);

        $controller = new CorrespondanceController();
        $controller->setAgentGradeService($agentGradeService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);
        $controller->setUtilService($utilService);
        return $controller;
    }
}