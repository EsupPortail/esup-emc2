<?php

namespace Agent\View\Helper;

use Agent\Provider\Parametre\AgentParametres;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class AgentOngletViewHelperFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentOngletViewHelper
    {
        /**
         * @var CampagneService $campagneService
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $campagneService = $container->get(CampagneService::class);
        $parametreService = $container->get(ParametreService::class);
        $parametres = $parametreService->getParametresByCategorieCode(AgentParametres::TYPE);
        $userService = $container->get(UserService::class);

        $helper = new AgentOngletViewHelper();
        $helper->setCampagneService($campagneService);
        $helper->setParametres($parametres);
        $helper->setUserService($userService);
        return $helper;
    }
}
