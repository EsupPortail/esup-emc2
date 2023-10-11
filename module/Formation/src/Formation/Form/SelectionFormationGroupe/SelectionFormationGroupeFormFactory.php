<?php

namespace Formation\Form\SelectionFormationGroupe;

use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionFormationGroupeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormationGroupeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionFormationGroupeForm
    {
        /**
         * @var FormationGroupeService $formationGroupeService
         */
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /** @var SelectionFormationGroupeForm $form */
        $form = new SelectionFormationGroupeForm();
        $form->setFormationGroupeService($formationGroupeService);
        return $form;
    }
}