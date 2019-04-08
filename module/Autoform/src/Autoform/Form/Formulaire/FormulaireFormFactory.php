<?php

namespace Autoform\Form\Formulaire;

use Zend\Form\FormElementManager;

class FormulaireFormFactory {

    public function __invoke(FormElementManager $container)
    {
        /** @var FormulaireHydrator $hydrator */
        $hydrator = $container->getServiceLocator()->get('HydratorManager')->get(FormulaireHydrator::class);

        /** @var  FormulaireForm $form */
        $form = new FormulaireForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}