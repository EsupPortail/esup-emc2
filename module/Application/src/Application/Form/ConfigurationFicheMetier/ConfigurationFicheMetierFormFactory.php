<?php

namespace Application\Form\ConfigurationFicheMetier;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfigurationFicheMetierFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ConfigurationFicheMetierForm
    {
        /**
         * @var ConfigurationFicheMetierHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ConfigurationFicheMetierHydrator::class);

        $form = new ConfigurationFicheMetierForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}