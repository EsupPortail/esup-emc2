<?php

namespace Carriere\Form\Categorie;

use Interop\Container\ContainerInterface;

class CategorieFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieForm
     */
    public function __invoke(ContainerInterface $container) : CategorieForm
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CategorieHydrator::class);

        /** @var CategorieForm $form */
        $form = new CategorieForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}