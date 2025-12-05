<?php

namespace Carriere\Controller;

use Carriere\Form\NiveauFonction\NiveauFonctionForm;
use Carriere\Service\NiveauFonction\NiveauFonctionService;
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
         * @var NiveauFonctionService $niveauFonctionService
         * @var NiveauFonctionForm $niveauFonctionForm
         */
        $niveauFonctionService = $container->get(NiveauFonctionService::class);
        $niveauFonctionForm = $container->get('FormElementManager')->get(NiveauFonctionForm::class);

        $controller = new NiveauFonctionController();
        $controller->setNiveauFonctionService($niveauFonctionService);
        $controller->setNiveauFonctionForm($niveauFonctionForm);
        return $controller;
    }

}
