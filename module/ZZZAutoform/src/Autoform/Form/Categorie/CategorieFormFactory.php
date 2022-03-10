<?php

namespace Autoform\Form\Categorie;

use Interop\Container\ContainerInterface;

class CategorieFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CategorieHydrator::class);

        /** @var  CategorieForm $form */
        $form = new CategorieForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}