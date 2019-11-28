<?php

namespace Application\Form\ConfigurationFicheMetier;

use Interop\Container\ContainerInterface;

class ConfigurationFicheMetierFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationFicheMetierForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ConfigurationFicheMetierHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ConfigurationFicheMetierHydrator::class);

        /** @var  ConfigurationFicheMetierForm $form */
        $form = new ConfigurationFicheMetierForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}