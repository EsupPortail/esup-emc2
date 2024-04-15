<?php

namespace Formation\Form\SelectionGestionnaire;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionGestionnaireFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return SelectionGestionnaireForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionGestionnaireForm
    {
        /**
         * @var FormationService $formationService
         * @var SelectionGestionnaireHydrator $hydrator
         */
        $formationService = $container->get(FormationService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionGestionnaireHydrator::class);

        $form = new SelectionGestionnaireForm();
        $form->setFormationService($formationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}