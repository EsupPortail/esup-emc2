<?php

namespace Element\Form\Application;

use Element\Service\ApplicationTheme\ApplicationThemeService;
use Interop\Container\ContainerInterface;

class ApplicationFormFactory {

    public function __invoke(ContainerInterface $container)
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