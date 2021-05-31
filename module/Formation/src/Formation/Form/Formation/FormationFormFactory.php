<?php

namespace Formation\Form\Formation;

use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;

class FormationFormFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationGroupeService $formationGroupeService
         */
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var FormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationHydrator::class);

        /** @var FormationForm $form */
        $form = new FormationForm();
        $form->setFormationGroupeService($formationGroupeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}