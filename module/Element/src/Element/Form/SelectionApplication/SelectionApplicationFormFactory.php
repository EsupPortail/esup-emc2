<?php

namespace Element\Form\SelectionApplication;

use Element\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionApplicationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionApplicationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionApplicationForm
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /**
         * @var SelectionApplicationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionApplicationHydrator::class);

        $form = new SelectionApplicationForm();
        $form->setHydrator($hydrator);
        $form->setApplicationService($applicationService);
        return $form;
    }
}