<?php

namespace Carriere\Form\SelectionnerFamilleProfessionnelle;

use Interop\Container\ContainerInterface;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerFamilleProfessionnelleHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerFamilleProfessionnelleHydrator
    {
        /** @var FamilleProfessionnelleService $familleProfessionnelleService */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $hydrator = new SelectionnerFamilleProfessionnelleHydrator();
        $hydrator->setFamilleProfessionnelleService($familleProfessionnelleService);

        return $hydrator;
    }
}