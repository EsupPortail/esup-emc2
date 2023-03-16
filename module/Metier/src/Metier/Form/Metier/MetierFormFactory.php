<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MetierFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MetierForm
    {
        /**
         * @var MetierHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(MetierHydrator::class);

        /**
         * @var CategorieService $categorieService
         * @var DomaineService $domaineService
         */
        $categorieService = $container->get(CategorieService::class);
        $domaineService = $container->get(DomaineService::class);

        $form = new MetierForm();
        $form->setCategorieService($categorieService);
        $form->setDomaineService($domaineService);
        $form->setHydrator($hydrator);

        return $form;
    }
}