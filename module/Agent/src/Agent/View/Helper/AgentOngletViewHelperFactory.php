<?php

namespace Agent\View\Helper;

use Application\Provider\Parametre\AgentParametres;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class AgentOngletViewHelperFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentOngletViewHelper
    {
        /**
         * @var ParametreService $parametreService
         */
        $parametreService = $container->get(ParametreService::class);
        $parametres = $parametreService->getParametresByCategorieCode(AgentParametres::TYPE);

        $helper = new AgentOngletViewHelper();
        $helper->setParametres($parametres);
        return $helper;
    }
}
