<?php

namespace FicheMetier\Form\FicheMetierIdentification;

use Referentiel\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FicheMetierIdentificationHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierIdentificationHydrator
    {
        /**
         * @var ReferentielService $referentielService
         */
        $referentielService = $container->get(ReferentielService::class);

        $hydrator = new FicheMetierIdentificationHydrator();
        $hydrator->setReferentielService($referentielService);
        return $hydrator;
    }
}
