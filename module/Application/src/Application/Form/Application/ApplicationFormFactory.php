<?php

namespace Application\Form\Application;

use Application\Entity\Db\ApplicationGroupe;
use Application\Service\Application\ApplicationGroupeService;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class ApplicationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationGroupe $applicationGroupeService
         * @var FormationService $formationService
         */
        $applicationGroupeService = $container->get(ApplicationGroupeService::class);
        $formationService = $container->get(FormationService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationHydrator::class);

        $form = new ApplicationForm();
        $form->setApplicationGroupeService($applicationGroupeService);
        $form->setFormationService($formationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}