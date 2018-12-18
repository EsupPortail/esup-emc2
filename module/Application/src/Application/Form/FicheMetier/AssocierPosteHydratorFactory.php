<?php

namespace Application\Form\FicheMetier;

use Application\Service\Poste\PosteService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AssocierPosteHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var PosteService $posteService */
        $posteService = $parentLocator->get(PosteService::class);

        $hydrator = new AssocierPosteHydrator();
        $hydrator->setPosteService($posteService);

        return $hydrator;
    }

}