<?php

namespace UnicaenDocument\Controller;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Form\Macro\MacroForm;
use UnicaenDocument\Service\Macro\MacroService;

class MacroControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MacroController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MacroService $macroService
         */
        $macroService = $container->get(MacroService::class);

        /**
         * @var MacroForm $macroForm
         */
        $macroForm = $container->get('FormElementManager')->get(MacroForm::class);

        $controller = new MacroController();
        $controller->setMacroService($macroService);
        $controller->setMacroForm($macroForm);
        return $controller;
    }
}