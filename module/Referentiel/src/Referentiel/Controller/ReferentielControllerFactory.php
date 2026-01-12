<?php

namespace Referentiel\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Form\Referentiel\ReferentielForm;
use Referentiel\Service\Referentiel\ReferentielService;

class ReferentielControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ReferentielController
    {
        /**
         * @var ReferentielService $referentielService
         * @var ReferentielForm $referentielForm
         */
        $referentielService = $container->get(ReferentielService::class);
        $referentielForm = $container->get('FormElementManager')->get(ReferentielForm::class);

        $controller = new ReferentielController();
        $controller->setReferentielService($referentielService);
        $controller->setReferentielForm($referentielForm);
        return $controller;
    }
}
