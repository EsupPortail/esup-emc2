<?php

namespace Formation\Form\FormationElement;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationElementForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationElementForm
    {
        /**
         * @var FormationService $competenceService
         */
        $competenceService = $container->get(FormationService::class);

        /** @var FormationElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationElementHydrator::class);

        $form = new FormationElementForm();
        $form->setFormationService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}