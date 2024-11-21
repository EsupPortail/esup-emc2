<?php

namespace Application\Service\Macro;

use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class MacroServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return MacroService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MacroService
    {
        /**
         * @var PhpRenderer $renderer
         * @var ParametreService $parametreService
        */
        $renderer = $container->get('ViewRenderer');
        $parametreService = $container->get(ParametreService::class);

        $service = new MacroService();
        $service->setRenderer($renderer);
        $service->setParametreService($parametreService);
        return $service;
    }
}