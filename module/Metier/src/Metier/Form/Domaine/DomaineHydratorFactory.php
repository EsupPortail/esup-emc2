<?php

namespace Metier\Form\Domaine;

use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DomaineHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DomaineHydrator
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         */
        $familleService = $container->get(FamilleProfessionnelleService::class);

        $hydrator = new DomaineHydrator();
        $hydrator->setFamilleProfessionnelleService($familleService);
        return $hydrator;
    }
}