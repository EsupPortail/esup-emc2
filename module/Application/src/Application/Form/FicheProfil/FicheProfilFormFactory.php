<?php

namespace Application\Form\FicheProfil;

use Application\Service\FichePoste\FichePosteService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;

class FicheProfilFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FichePosteService $fichePosteService
         * @var StructureService $structureService
         */
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);

        /** @var FicheProfilHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FicheProfilHydrator::class);

        $form = new FicheProfilForm();
        $form->setFichePosteService($fichePosteService);
        $form->setStructureService($structureService);
        $form->setHydrator($hydrator);
        return $form;
    }
}