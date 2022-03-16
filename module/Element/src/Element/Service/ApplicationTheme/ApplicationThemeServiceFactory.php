<?php

namespace Element\Service\ApplicationTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class ApplicationThemeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationThemeService
     */
    public function __invoke(ContainerInterface $container) : ApplicationThemeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var ApplicationThemeService $service */
        $service = new ApplicationThemeService();
        $service->setEntityManager($entityManager);
        $service->setRenderer($renderer);
        return $service;
    }
}
