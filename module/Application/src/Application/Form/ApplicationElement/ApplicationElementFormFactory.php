<?php

namespace Application\Form\ApplicationElement;

use Application\Service\Application\ApplicationService;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
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
         * @var MaitriseNiveauService $MaitriseNiveauService
         */
        $applicationService = $container->get(ApplicationService::class);
        $MaitriseNiveauService = $container->get(MaitriseNiveauService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationElementHydrator::class);

        /** @var ApplicationElementForm $form */
        $form = new ApplicationElementForm();
        $form->setApplicationService($applicationService);
        $form->setMaitriseNiveauService($MaitriseNiveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}