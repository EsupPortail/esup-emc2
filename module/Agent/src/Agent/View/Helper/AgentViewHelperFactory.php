<?php

namespace Agent\View\Helper;

use Agent\Provider\Parametre\AgentParametres;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;


class AgentViewHelperFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentViewHelper
    {
        /** @var ParametreService $parametreService */
        $parametreService = $container->get(ParametreService::class);
        $parametres = $parametreService->getParametresByCategorieCode(AgentParametres::TYPE);

        $helper = new AgentViewHelper();
        $helper->setParametres($parametres);
        return $helper;
    }
}