<?php

namespace Metier\Controller;

use Interop\Container\ContainerInterface;
use Metier\Form\Reference\ReferenceForm;
use Metier\Service\Metier\MetierService;
use Metier\Service\Reference\ReferenceService;

class ReferenceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierService $metierService
         * @var ReferenceService $referenceService
         * @var ReferenceForm $referenceForm
         */
        $metierService = $container->get(MetierService::class);
        $referenceService = $container->get(ReferenceService::class);
        $referenceForm = $container->get('FormElementManager')->get(ReferenceForm::class);

        $controller = new ReferenceController();
        $controller->setMetierService($metierService);
        $controller->setReferenceService($referenceService);
        $controller->setReferenceForm($referenceForm);
        return $controller;
    }
}