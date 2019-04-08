<?php

namespace Autoform\Form\Categorie;

use Zend\Form\FormElementManager;

class CategorieFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(CategorieHydrator::class);

        /** @var  CategorieForm $form */
        $form = new CategorieForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}