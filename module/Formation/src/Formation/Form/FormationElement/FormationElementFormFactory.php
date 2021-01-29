<?php

namespace Formation\Form\FormationElement;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationElementForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $competenceService
         */
        $competenceService = $container->get(FormationService::class);

        /** @var FormationElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationElementHydrator::class);

        /** @var FormationElementForm $form */
        $form = new FormationElementForm();
        $form->setFormationService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}