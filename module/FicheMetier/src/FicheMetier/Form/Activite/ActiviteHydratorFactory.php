<?php

namespace FicheMetier\Form\Activite;

use Referentiel\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActiviteHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActiviteHydrator
    {
        /**
         * @var ReferentielService $referentielService
         */
        $referentielService = $container->get(ReferentielService::class);

        $hydrator = new ActiviteHydrator();
        $hydrator->setReferentielService($referentielService);
        return $hydrator;
    }
}
