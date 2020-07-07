<?php

namespace Application\Form\ParcoursDeFormation;

use Application\Service\Formation\FormationService;
use Application\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var MetierService $metierService
         */
        $formationService = $container->get(FormationService::class);
        $metierService = $container->get(MetierService::class);

        /** @var ParcoursDeFormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ParcoursDeFormationHydrator::class);

        /** @var ParcoursDeFormationForm $form */
        $form = new ParcoursDeFormationForm();
        $form->setFormationService($formationService);
        $form->setMetierService($metierService);
        $form->setHydrator($hydrator);
        return $form;
    }
}