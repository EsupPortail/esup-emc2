<?php

namespace Carriere\Controller;

use Carriere\Form\NiveauFonction\NiveauFonctionForm;
use Carriere\Service\NiveauFonction\NiveauFonctionService;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauFonctionControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): NiveauFonctionController
    {
        /**
         * @var CodeFonctionService $codeFonctionService
         * @var NiveauFonctionService $niveauFonctionService
         * @var NiveauFonctionForm $niveauFonctionForm
         */
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $niveauFonctionService = $container->get(NiveauFonctionService::class);
        $niveauFonctionForm = $container->get('FormElementManager')->get(NiveauFonctionForm::class);

        $controller = new NiveauFonctionController();
        $controller->setCodeFonctionService($codeFonctionService);
        $controller->setNiveauFonctionService($niveauFonctionService);
        $controller->setNiveauFonctionForm($niveauFonctionForm);
        return $controller;
    }

}
