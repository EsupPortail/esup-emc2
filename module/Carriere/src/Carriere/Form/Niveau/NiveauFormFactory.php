<?php

namespace Carriere\Form\Niveau;

use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauForm
    {
        /**
         * @var CategorieService $categorieService
         * @var NiveauHydrator $hydrator
         */
        $categorieService = $container->get(CategorieService::class);
        $hydrator = $container->get('HydratorManager')->get(NiveauHydrator::class);

        $form = new NiveauForm();
        $form->setCategorieService($categorieService);
        $form->setHydrator($hydrator);
        return $form;
    }
}