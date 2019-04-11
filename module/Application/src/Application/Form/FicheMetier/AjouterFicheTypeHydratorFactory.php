<?php

namespace Application\Form\FicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class AjouterFicheTypeHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);

        /** @var AjouterFicheTypeHydrator $hydrator */
        $hydrator = new AjouterFicheTypeHydrator();
        $hydrator->setFicheMetierService($ficheMetierService);
        return $hydrator;
    }
}