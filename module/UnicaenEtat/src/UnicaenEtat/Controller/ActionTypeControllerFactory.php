<?php

namespace UnicaenEtat\Controller;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Form\ActionType\ActionTypeForm;
use UnicaenEtat\Service\ActionType\ActionTypeService;

class ActionTypeControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActionTypeService $actionTypeService
         */
        $actionTypeService = $container->get(ActionTypeService::class);

        /**
         * @var ActionTypeForm $actionTypeForm
         */
        $actionTypeForm = $container->get('FormElementManager')->get(ActionTypeForm::class);

        $controller = new ActionTypeController();
        $controller->setActionTypeService($actionTypeService);
        $controller->setActionTypeForm($actionTypeForm);
        return $controller;
    }

}