<?php

namespace UnicaenGlossaire\Controller;

use Interop\Container\ContainerInterface;
use UnicaenGlossaire\Form\Definition\DefinitionForm;
use UnicaenGlossaire\Service\Definition\DefinitionService;

class DefinitionControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return DefinitionController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DefinitionService $definitionService
         */
        $definitionService = $container->get(DefinitionService::class);

        /**
         * @var DefinitionForm $definitionForm
         */
        $definitionForm = $container->get('FormElementManager')->get(DefinitionForm::class);

        $controller = new DefinitionController();
        $controller->setDefinitionService($definitionService);
        $controller->setDefinitionForm($definitionForm);
        return $controller;
    }
}