<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;

class AjouterFicheMetierHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);

        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = new AjouterFicheMetierHydrator();
        $hydrator->setFicheMetierService($ficheMetierService);
        return $hydrator;
    }
}