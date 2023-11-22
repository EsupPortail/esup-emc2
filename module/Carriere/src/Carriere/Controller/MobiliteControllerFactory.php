<?php

namespace Carriere\Controller;

use Carriere\Form\Mobilite\MobiliteForm;
use Carriere\Service\Mobilite\MobiliteService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MobiliteControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MobiliteController
    {
        /**
         * @var MobiliteService $mobiliteService
         * @var MobiliteForm $mobiliteForm
         */
        $mobiliteService = $container->get(MobiliteService::class);
        $mobiliteForm = $container->get('FormElementManager')->get(MobiliteForm::class);

        $controller = new MobiliteController();
        $controller->setMobiliteService($mobiliteService);
        $controller->setMobiliteForm($mobiliteForm);
        return $controller;
    }
}