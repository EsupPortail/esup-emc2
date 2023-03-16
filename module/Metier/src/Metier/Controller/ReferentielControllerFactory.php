<?php

namespace Metier\Controller;

use Interop\Container\ContainerInterface;
use Metier\Form\Referentiel\ReferentielForm;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferentielControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferentielController
    {
        /**
         * @var ReferentielService $referentielService
         */
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var ReferentielForm $referentielForm
         */
        $referentielForm = $container->get('FormElementManager')->get(ReferentielForm::class);

        $controller = new ReferentielController();
        $controller->setReferentielService($referentielService);
        $controller->setReferentielForm($referentielForm);
        return $controller;
    }
}