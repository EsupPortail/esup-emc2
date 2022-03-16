<?php

namespace Element\Form\ApplicationTheme;

use Interop\Container\ContainerInterface;

class ApplicationThemeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationThemeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ApplicationThemeHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(ApplicationThemeHydrator::class);

        /** @var ApplicationThemeForm $form */
        $form = new ApplicationThemeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}