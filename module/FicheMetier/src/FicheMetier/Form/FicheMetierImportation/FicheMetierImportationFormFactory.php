<?php

namespace FicheMetier\Form\FicheMetierImportation;

use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FicheMetierImportationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierImportationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FicheMetierImportationForm
    {
        /**
         * @var ReferentielService $referentielService
         * @var FichierMetierImportationHydrator $hydrator
         */
        $referentielService = $container->get(ReferentielService::class);
        $hydrator = $container->get('HydratorManager')->get(FichierMetierImportationHydrator::class);

        $form = new FicheMetierImportationForm();
        $form->setReferentielService($referentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}