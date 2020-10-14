<?php

namespace UnicaenEtat\Controller;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Form\Action\ActionForm;
use UnicaenEtat\Service\Action\ActionService;

class ActionControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActionService $actionService
         */
        $actionService = $container->get(ActionService::class);

        /**
         * @var ActionForm $actionForm
         */
        $actionForm = $container->get('FormElementManager')->get(ActionForm::class);

        $controller = new ActionController();
        $controller->setActionService($actionService);
        $controller->setActionForm($actionForm);
        return $controller;
    }

}