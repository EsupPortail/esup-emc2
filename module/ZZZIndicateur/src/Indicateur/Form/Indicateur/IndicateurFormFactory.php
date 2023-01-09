<?php

namespace Indicateur\Form\Indicateur;

use Interop\Container\ContainerInterface;
use Laminas\Form\FormElementManager;

class IndicateurFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var IndicateurHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(IndicateurHydrator::class);

        /** @var IndicateurForm $form */
        $form = new IndicateurForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}