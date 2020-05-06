<?php

namespace Application\Form\MetierReference;

use Application\Service\Metier\MetierService;
use Application\Service\MetierReferentiel\MetierReferentielService;
use Interop\Container\ContainerInterface;

class MetierReferenceHydraytorFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierReferenceHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierService $metierService
         * @var MetierReferentielService $referentielService
         */
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(MetierReferentielService::class);

        $hydrator = new MetierReferenceHydrator();
        $hydrator->setMetierService($metierService);
        $hydrator->setMetierReferentielService($referentielService);
        return $hydrator;
    }
}