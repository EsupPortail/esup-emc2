<?php

namespace Formation\Form\SelectionFormationGroupe;

use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;

class SelectionFormationGroupeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormationGroupeForm
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