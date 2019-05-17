<?php

namespace Application\Form\RessourceRh;

use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class DomaineHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         */
        $familleService = $manager->getServiceLocator()->get(FamilleProfessionnelleService::class);

        /** @var DomaineHydrator $hydrator */
        $hydrator = new DomaineHydrator();
        $hydrator->setFamilleProfessionnelleService($familleService);
        return $hydrator;
    }
}