<?php

namespace Application\Form\FicheMetier;

use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class ApplicationsFormFactory{

    public function __invoke(ContainerInterface $container)
    {
        /** @var ApplicationService $applicationService*/
        $applicationService = $container->get(ApplicationService::class);

        /** @var ApplicationsHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationsHydrator::class);

        $form = new ApplicationsForm();
        $form->setApplicationService($applicationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}