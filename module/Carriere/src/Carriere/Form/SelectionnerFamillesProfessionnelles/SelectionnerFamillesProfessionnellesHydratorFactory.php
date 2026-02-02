<?php

namespace Carriere\Form\SelectionnerFamillesProfessionnelles;

use Interop\Container\ContainerInterface;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerFamillesProfessionnellesHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerFamillesProfessionnellesHydrator
    {
        /** @var FamilleProfessionnelleService $familleProfessionnelleService */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $hydrator = new SelectionnerFamillesProfessionnellesHydrator();
        $hydrator->setFamilleProfessionnelleService($familleProfessionnelleService);

        return $hydrator;
    }
}