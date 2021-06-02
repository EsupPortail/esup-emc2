<?php

namespace Application\Controller;

use Application\Form\FicheProfil\FicheProfilForm;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FicheProfil\FicheProfilService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Exporter\ExporterService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FicheProfilControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ExporterService $exporterService
         * @var FicheProfilService $ficheProfilService
         * @var FichePosteService $fichePosteService
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         */
        $exporterService = $container->get(ExporterService::class);
        $ficheProfilService = $container->get(FicheProfilService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var FicheProfilForm $ficheProfilForm
         */
        $ficheProfilForm = $container->get('FormElementManager')->get(FicheProfilForm::class);

        $controller = new FicheProfilController();
        $controller->setExporterService($exporterService);
        $controller->setFicheProfilService($ficheProfilService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setParametreService($parametreService);
        $controller->setStructureService($structureService);
        $controller->setFicheProfilForm($ficheProfilForm);
        return $controller;
    }
}