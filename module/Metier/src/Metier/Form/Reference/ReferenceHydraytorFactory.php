<?php

namespace Metier\Form\Reference;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Metier\Service\Referentiel\ReferentielService;

class ReferenceHydraytorFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierService $metierService
         * @var ReferentielService $referentielService
         */
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(ReferentielService::class);

        $hydrator = new ReferenceHydrator();
        $hydrator->setMetierService($metierService);
        $hydrator->setReferentielService($referentielService);
        return $hydrator;
    }
}