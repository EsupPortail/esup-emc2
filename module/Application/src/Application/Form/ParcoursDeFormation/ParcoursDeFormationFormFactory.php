<?php

namespace Application\Form\ParcoursDeFormation;

use Carriere\Service\Categorie\CategorieService;
use Formation\Service\Formation\FormationService;
use Metier\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var FormationService $formationService
         * @var MetierService $metierService
         */
        $categorieService = $container->get(CategorieService::class);
        $formationService = $container->get(FormationService::class);
        $metierService = $container->get(MetierService::class);

        /** @var ParcoursDeFormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ParcoursDeFormationHydrator::class);

        /** @var ParcoursDeFormationForm $form */
        $form = new ParcoursDeFormationForm();
        $form->setCategorieService($categorieService);
        $form->setFormationService($formationService);
        $form->setMetierService($metierService);
        $form->setHydrator($hydrator);
        return $form;
    }
}