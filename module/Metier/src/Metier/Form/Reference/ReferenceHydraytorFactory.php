<?php

namespace Metier\Form\Reference;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Referentiel\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferenceHydraytorFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferenceHydrator
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