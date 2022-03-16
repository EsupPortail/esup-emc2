<?php

namespace Carriere\Form\Niveau;

use Interop\Container\ContainerInterface;

class NiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var NiveauHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(NiveauHydrator::class);

        $form = new NiveauForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}