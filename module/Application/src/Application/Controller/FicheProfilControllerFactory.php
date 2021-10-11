<?php

namespace Application\Controller;

use Application\Form\FicheProfil\FicheProfilForm;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FicheProfil\FicheProfilService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Contenu\ContenuService;
use UnicaenRenderer\Service\Template\TemplateService;

class FicheProfilControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilController
     */
    public function __invoke(ContainerInterface $container) : FicheProfilController
    {
        /**
         * @var ContenuService $contenuService
         * @var FichePosteService $fichePosteService
         * @var FicheProfilService $ficheProfilService
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         */
        $contenuService = $container->get(ContenuService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $ficheProfilService = $container->get(FicheProfilService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var FicheProfilForm $ficheProfilForm
         */
        $ficheProfilForm = $container->get('FormElementManager')->get(FicheProfilForm::class);

        $controller = new FicheProfilController();
        $controller->setContenuService($contenuService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setFicheProfilService($ficheProfilService);
        $controller->setParametreService($parametreService);
        $controller->setStructureService($structureService);
        $controller->setFicheProfilForm($ficheProfilForm);
        return $controller;
    }
}