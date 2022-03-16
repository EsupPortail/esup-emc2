<?php

namespace Element\Form\CompetenceTheme;

use Interop\Container\ContainerInterface;

class CompetenceThemeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceThemeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceThemeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceThemeHydrator::class);

        /** @var CompetenceThemeForm $form */
        $form = new CompetenceThemeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}