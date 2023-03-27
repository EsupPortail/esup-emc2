<?php

namespace Element\Form\ApplicationElement;

use Element\Service\Application\ApplicationService;
use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ApplicationElementForm
    {
        /**
         * @var ApplicationService $applicationService
         * @var NiveauService $MaitriseNiveauService
         */
        $applicationService = $container->get(ApplicationService::class);
        $MaitriseNiveauService = $container->get(NiveauService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationElementHydrator::class);

        $form = new ApplicationElementForm();
        $form->setApplicationService($applicationService);
        $form->setNiveauService($MaitriseNiveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}