<?php

namespace Metier\Controller;

use Interop\Container\ContainerInterface;
use Metier\Form\Metier\MetierForm;
use Metier\Form\MetierNiveau\MetierNiveauForm;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Metier\Service\Metier\MetierService;
use Metier\Service\MetierNiveau\MetierNiveauService;
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
         * @var MetierNiveauService $metierNiveauService
         * @var ReferentielService $referentielService
         */
        $domaineService = $container->get(DomaineService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $metierService = $container->get(MetierService::class);
        $metierNiveauService = $container->get(MetierNiveauService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var MetierForm $metierForm
         * @var MetierNiveauForm $metierNiveauForm
         */
        $metierForm = $container->get('FormElementManager')->get(MetierForm::class);
        $metierNiveauForm = $container->get('FormElementManager')->get(MetierNiveauForm::class);

        /** @var MetierController $controller */
        $controller = new MetierController();
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setMetierService($metierService);
        $controller->setMetierNiveauService($metierNiveauService);
        $controller->setReferentielService($referentielService);
        $controller->setMetierForm($metierForm);
        $controller->setMetierNiveauForm($metierNiveauForm);

        return $controller;
    }
}
