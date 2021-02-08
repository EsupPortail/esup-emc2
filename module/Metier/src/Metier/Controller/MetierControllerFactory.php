<?php

namespace Metier\Controller;

use Interop\Container\ContainerInterface;
use Metier\Form\Metier\MetierForm;
use Metier\Form\Reference\ReferenceForm;
use Metier\Form\Referentiel\ReferentielForm;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Metier\Service\Metier\MetierService;
use Metier\Service\Referentiel\ReferentielService;

class MetierControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DomaineService $domaineService
         * @var FamilleProfessionnelleService $familleService
         * @var MetierService $metierService
         * @var ReferentielService $referentielService
         */
        $domaineService = $container->get(DomaineService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var MetierForm $metierForm
         * @var ReferenceForm $metierReferenceForm
         * @var ReferentielForm $metierReferentielForm
         */
        $metierForm = $container->get('FormElementManager')->get(MetierForm::class);

        /** @var MetierController $controller */
        $controller = new MetierController();
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setMetierService($metierService);
        $controller->setReferentielService($referentielService);
        $controller->setMetierForm($metierForm);

        return $controller;
    }
}
