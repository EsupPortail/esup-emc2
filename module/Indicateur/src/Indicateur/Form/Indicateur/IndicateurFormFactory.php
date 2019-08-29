<?php

namespace Indicateur\Form\Indicateur;

use Zend\Form\FormElementManager;

class IndicateurFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var IndicateurHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(IndicateurHydrator::class);

        /** @var IndicateurForm $form */
        $form = new IndicateurForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}