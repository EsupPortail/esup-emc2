<?php

namespace Formation\Form\FormationTheme;

use Interop\Container\ContainerInterface;

class FormationThemeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationThemeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationThemeHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(FormationThemeHydrator::class);

        /** @var FormationThemeForm $form */
        $form = new FormationThemeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}