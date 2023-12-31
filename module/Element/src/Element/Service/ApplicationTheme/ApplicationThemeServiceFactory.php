<?php

namespace Element\Service\ApplicationTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationThemeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationThemeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ApplicationThemeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ApplicationThemeService $service */
        $service = new ApplicationThemeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
