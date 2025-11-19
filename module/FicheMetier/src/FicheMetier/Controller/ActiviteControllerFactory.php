<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\Activite\ActiviteForm;
use FicheMetier\Service\Activite\ActiviteService;
use FicheReferentiel\Form\Importation\ImportationForm;
use Metier\Service\Referentiel\ReferentielService;
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
         * @var ImportationForm $importationForm
         */
        $activiteService = $container->get(ActiviteService::class);
        $referentielService = $container->get(ReferentielService::class);
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $importationForm = $container->get('FormElementManager')->get(ImportationForm::class);

        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setReferentielService($referentielService);
        $controller->setActiviteForm($activiteForm);
        $controller->setImportationForm($importationForm);
        return $controller;
    }

}
