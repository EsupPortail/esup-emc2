<?php

namespace EntretienProfessionnel\Service\Url;

use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;

class UrlServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return UrlService
     */
    public function __invoke(ContainerInterface $container) : UrlService
    {
        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        $service = new UrlService();
        $service->setRenderer($renderer);
        return $service;
    }
}