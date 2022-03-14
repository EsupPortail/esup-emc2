<?php

namespace Application\Controller;

use Application\Form\FicheProfil\FicheProfilForm;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FicheProfil\FicheProfilService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;


class FicheProfilControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilController
     */
    public function __invoke(ContainerInterface $container) : FicheProfilController
    {
        /**
         * @var RenduService $renduService
         * @var FichePosteService $fichePosteService
         * @var FicheProfilService $ficheProfilService
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         */
        $renduService = $container->get(RenduService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $ficheProfilService = $container->get(FicheProfilService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var FicheProfilForm $ficheProfilForm
         */
        $ficheProfilForm = $container->get('FormElementManager')->get(FicheProfilForm::class);

        $controller = new FicheProfilController();
        $controller->setRenduService($renduService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setFicheProfilService($ficheProfilService);
        $controller->setParametreService($parametreService);
        $controller->setStructureService($structureService);
        $controller->setFicheProfilForm($ficheProfilForm);
        return $controller;
    }
}