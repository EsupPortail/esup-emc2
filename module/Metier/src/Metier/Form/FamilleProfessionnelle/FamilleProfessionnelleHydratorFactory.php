<?php

namespace Metier\Form\FamilleProfessionnelle;

use Carriere\Service\Correspondance\CorrespondanceService;
use Psr\Container\ContainerInterface;

class FamilleProfessionnelleHydratorFactory
{

    public function __invoke(ContainerInterface $container): FamilleProfessionnelleHydrator
    {
        /**
         * @var CorrespondanceService $correspondanceService
         */
        $correspondanceService = $container->get(CorrespondanceService::class);

        $hydrator = new FamilleProfessionnelleHydrator();
        $hydrator->setCorrespondanceService($correspondanceService);
        return $hydrator;
    }
}
