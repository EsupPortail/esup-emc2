<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Provider\Parametre\AgentParametres;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;


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