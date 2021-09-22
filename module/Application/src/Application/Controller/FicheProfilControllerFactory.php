<?php

namespace Application\Controller;

use Application\Form\FicheProfil\FicheProfilForm;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FicheProfil\FicheProfilService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Contenu\ContenuService;

class FicheProfilControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilController
     */
    public function __invoke(ContainerInterface $container) : FicheProfilController
    {
        /**
         * @var ContenuService $contenuService
         * @var FicheProfilService $ficheProfilService
         * @var FichePosteService $fichePosteService
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         */
        $contenuService = $container->get(ContenuService::class);
        $ficheProfilService = $container->get(FicheProfilService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var FicheProfilForm $ficheProfilForm
         */
        $ficheProfilForm = $container->get('FormElementManager')->get(FicheProfilForm::class);

        $controller = new FicheProfilController();
        $controller->setContenuService($contenuService);
        $controller->setFicheProfilService($ficheProfilService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setParametreService($parametreService);
        $controller->setStructureService($structureService);
        $controller->setFicheProfilForm($ficheProfilForm);
        return $controller;
    }
}