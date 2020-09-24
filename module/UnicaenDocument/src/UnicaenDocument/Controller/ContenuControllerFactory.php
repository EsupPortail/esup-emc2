<?php

namespace UnicaenDocument\Controller;

use Interop\Container\ContainerInterface;
use UnicaenDocument\Form\Contenu\ContenuForm;
use UnicaenDocument\Service\Contenu\ContenuService;

class ContenuControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ContenuController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ContenuService $contenuService
         */
        $contenuService = $container->get(ContenuService::class);

        /**
         * @var ContenuForm $contentForm
         */
        $contentForm = $container->get('FormElementManager')->get(ContenuForm::class);

        $controller = new ContenuController();
        $controller->setContenuService($contenuService);
        $controller->setContenuForm($contentForm);
        return $controller;
    }
}