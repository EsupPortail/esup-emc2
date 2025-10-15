<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
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
         * @var FamilleProfessionnelleService $familleProfessionnelService
         */
        $categorieService = $container->get(CategorieService::class);
        $familleProfessionnelService = $container->get(FamilleProfessionnelleService::class);

        $form = new MetierForm();
        $form->setCategorieService($categorieService);
        $form->setFamilleProfessionnelleService($familleProfessionnelService);
        $form->setHydrator($hydrator);

        return $form;
    }
}