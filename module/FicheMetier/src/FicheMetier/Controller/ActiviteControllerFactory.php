<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\Activite\ActiviteForm;
use FicheMetier\Service\Activite\ActiviteService;
use Referentiel\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActiviteControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActiviteController
    {
        /**
         * @var ActiviteService $activiteService
         * @var ReferentielService $referentielService
         * @var ActiviteForm $activiteForm
         */
        $activiteService = $container->get(ActiviteService::class);
        $referentielService = $container->get(ReferentielService::class);
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);

        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setReferentielService($referentielService);
        $controller->setActiviteForm($activiteForm);
        return $controller;
    }

}
