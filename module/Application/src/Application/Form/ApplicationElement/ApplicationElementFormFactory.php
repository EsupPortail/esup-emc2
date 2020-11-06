<?php

namespace Application\Form\ApplicationElement;

use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class ApplicationElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationElementHydrator::class);

        /** @var ApplicationElementForm $form */
        $form = new ApplicationElementForm();
        $form->setApplicationService($applicationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}