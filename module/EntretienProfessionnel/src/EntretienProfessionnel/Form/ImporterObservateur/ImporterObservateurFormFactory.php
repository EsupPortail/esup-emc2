<?php

namespace EntretienProfessionnel\Form\ImporterObservateur;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImporterObservateurFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImporterObservateurForm
    {
        /**
         * @var CampagneService $campagneService
         * @var ImporterObservateurHydrator $hydrator
         */
        $campagneService = $container->get(CampagneService::class);
        $hydrator = $container->get('HydratorManager')->get(ImporterObservateurHydrator::class);

        $form = new ImporterObservateurForm();
        $form->setCampagneService($campagneService);
        $form->setHydrator($hydrator);
        return $form;
    }
}