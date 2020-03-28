<?php

namespace Application\Form\SelectionApplication;

use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class SelectionApplicationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionApplicationForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /**
         * @var SelectionApplicationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionApplicationHydrator::class);

        /** @var SelectionApplicationForm $form */
        $form = new SelectionApplicationForm();
        $form->setHydrator($hydrator);
        $form->setApplicationService($applicationService);
        return $form;
    }
}