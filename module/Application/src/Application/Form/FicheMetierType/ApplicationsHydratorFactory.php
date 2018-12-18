<?php

namespace Application\Form\FicheMetierType;


use Application\Service\Application\ApplicationService;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationsHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var ApplicationService $applicationService */
        $applicationService = $parentLocator->get(ApplicationService::class);

        $hydrator = new ApplicationsHydrator();
        $hydrator->setApplicationService($applicationService);

        return $hydrator;
    }
}