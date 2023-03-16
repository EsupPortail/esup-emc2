<?php

namespace Metier\Controller;

use Interop\Container\ContainerInterface;
use Metier\Form\Reference\ReferenceForm;
use Metier\Service\Metier\MetierService;
use Metier\Service\Reference\ReferenceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferenceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferenceController
    {
        /**
         * @var MetierService $metierService
         * @var ReferenceService $referenceService
         */
        $metierService = $container->get(MetierService::class);
        $referenceService = $container->get(ReferenceService::class);

        /**
         * @var ReferenceForm $referenceForm
         */
        $referenceForm = $container->get('FormElementManager')->get(ReferenceForm::class);

        $controller = new ReferenceController();
        $controller->setMetierService($metierService);
        $controller->setReferenceService($referenceService);
        $controller->setReferenceForm($referenceForm);
        return $controller;
    }
}