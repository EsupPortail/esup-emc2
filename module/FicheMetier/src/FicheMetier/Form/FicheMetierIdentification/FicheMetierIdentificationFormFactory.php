<?php

namespace FicheMetier\Form\FicheMetierIdentification;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

class FicheMetierIdentificationFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierIdentificationForm
    {
        /**
         * @var ReferentielService $referentielService
         * @var FicheMetierIdentificationHydrator $hydrator
         */
        $referentielService = $container->get(ReferentielService::class);
        $hydrator = $container->get('HydratorManager')->get(FicheMetierIdentificationHydrator::class);

        $form = new FicheMetierIdentificationForm();
        $form->setReferentielService($referentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
