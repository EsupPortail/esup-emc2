<?php

namespace Application\Form\FicheProfil;

use Application\Service\FichePoste\FichePosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;

class FicheProfilHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FichePosteService $fichePosteService
         * @var StructureService $structureService
         */
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);

        $hydrator = new FicheProfilHydrator();
        $hydrator->setFichePosteService($fichePosteService);
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }
}