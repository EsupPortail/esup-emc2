<?php

namespace Metier\Form\Metier;

use Application\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;

class MetierFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(MetierHydrator::class);

        /**
         * @var CategorieService $categorieService
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