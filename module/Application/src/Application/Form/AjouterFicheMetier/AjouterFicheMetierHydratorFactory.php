<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class AjouterFicheMetierHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);

        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = new AjouterFicheMetierHydrator();
        $hydrator->setFicheMetierService($ficheMetierService);
        return $hydrator;
    }
}