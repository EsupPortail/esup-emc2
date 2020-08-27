<?php

namespace Application\Form\ModifierRattachement;

use Application\Service\Categorie\CategorieService;
use Application\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;

class ModifierRattachementFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var MetierService $metierService
         */
        $categorieService = $container->get(CategorieService::class);
        $metierService = $container->get(MetierService::class);

        /** @var ModifierRattachementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ModifierRattachementHydrator::class);

        /** @var ModifierRattachementForm $form */
        $form = new ModifierRattachementForm();
        $form->setCategorieService($categorieService);
        $form->setMetierService($metierService);
        $form->setHydrator($hydrator);
        return $form;
    }
}