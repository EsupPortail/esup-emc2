<?php

namespace Application\Service\Macro;

use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        $service = new MacroService();
        $service->setRenderer($renderer);
        return $service;
    }
}