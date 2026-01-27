<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\Activite\ActiviteForm;
use FicheMetier\Service\Activite\ActiviteService;
use FicheMetier\Service\ActiviteElement\ActiviteElementService;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Referentiel\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class ActiviteControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActiviteController
    {
        /**
         * @var ActiviteService $activiteService
         * @var ActiviteElementService $activiteElementService
         * @var CodeFonctionService $codeFonctionService
         * @var FicheMetierService $ficheMetierService
         * @var ParametreService $parametreService
         * @var ReferentielService $referentielService
         * @var ActiviteForm $activiteForm
         */
        $activiteService = $container->get(ActiviteService::class);
        $activiteElementService = $container->get(ActiviteElementService::class);
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $parametreService = $container->get(ParametreService::class);
        $referentielService = $container->get(ReferentielService::class);
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);

        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setActiviteElementService($activiteElementService);
        $controller->setCodeFonctionService($codeFonctionService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setParametreService($parametreService);
        $controller->setReferentielService($referentielService);
        $controller->setActiviteForm($activiteForm);
        return $controller;
    }

}
