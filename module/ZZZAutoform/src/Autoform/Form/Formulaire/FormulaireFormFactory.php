<?php

namespace Autoform\Form\Formulaire;

use Interop\Container\ContainerInterface;

class FormulaireFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormulaireHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormulaireHydrator::class);

        /** @var  FormulaireForm $form */
        $form = new FormulaireForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}