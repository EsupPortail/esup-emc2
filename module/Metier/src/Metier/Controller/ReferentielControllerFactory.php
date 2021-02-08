<?php

namespace Metier\Controller;

use Interop\Container\ContainerInterface;
use Metier\Form\Referentiel\ReferentielForm;
use Metier\Service\Referentiel\ReferentielService;

class ReferentielControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielController
     */
    public function __invoke(ContainerInterface $container)
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