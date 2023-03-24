<?php 

namespace Element\Form\Application;

use Element\Service\ApplicationTheme\ApplicationThemeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ApplicationHydrator
    {
        /**
         * @var ApplicationThemeService $applicationGroupeService
         */
        $applicationGroupeService = $container->get(ApplicationThemeService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = new ApplicationHydrator();
        $hydrator->setApplicationThemeService($applicationGroupeService);
        return $hydrator;
    }
}