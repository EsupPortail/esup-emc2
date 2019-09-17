<?php

namespace Application\Form\FicheMetier;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationsFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationsForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var FormationsHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationsHydrator::class);

        /** @var FormationsForm $form */
        $form = new FormationsForm();
        $form->setFormationService($formationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}