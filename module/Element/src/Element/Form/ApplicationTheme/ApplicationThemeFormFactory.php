<?php

namespace Element\Form\ApplicationTheme;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationThemeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationThemeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ApplicationThemeForm
    {
        /** @var ApplicationThemeHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(ApplicationThemeHydrator::class);

        $form = new ApplicationThemeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}