<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeFonctionControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeFonctionController
    {
        /**
         * @var CodeFonctionService $codeFonctionService
         * @var CodeFonctionForm $codeFonctionForm
         */
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $codeFonctionForm = $container->get('FormElementManager')->get(CodeFonctionForm::class);

        $controller = new CodeFonctionController();
        $controller->setCodeFonctionService($codeFonctionService);
        $controller->setCodeFonctionForm($codeFonctionForm);
        return $controller;
    }
}
