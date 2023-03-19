<?php

namespace Carriere\Form\Categorie;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategorieFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CategorieForm
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CategorieHydrator::class);

        $form = new CategorieForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}