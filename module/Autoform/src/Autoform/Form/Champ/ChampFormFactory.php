<?php

namespace Autoform\Form\Champ;

use Zend\Form\FormElementManager;

class ChampFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var ChampHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ChampHydrator::class);

        /** @var  ChampForm $form */
        $form = new ChampForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}