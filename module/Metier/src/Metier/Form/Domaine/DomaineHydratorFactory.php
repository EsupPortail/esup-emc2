<?php

namespace Metier\Form\Domaine;

use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;

class DomaineHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineHydrator
     */
    public function __invoke(ContainerInterface $container) : DomaineHydrator
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         */
        $familleService = $container->get(FamilleProfessionnelleService::class);

        /** @var DomaineHydrator $hydrator */
        $hydrator = new DomaineHydrator();
        $hydrator->setFamilleProfessionnelleService($familleService);
        return $hydrator;
    }
}