<?php

namespace Element\Form\Application;

use Element\Service\ApplicationTheme\ApplicationThemeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ApplicationForm
    {
        /**
         * @var ApplicationThemeService $applicationGroupeService
         */
        $applicationGroupeService = $container->get(ApplicationThemeService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationHydrator::class);

        $form = new ApplicationForm();
        $form->setApplicationThemeService($applicationGroupeService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}