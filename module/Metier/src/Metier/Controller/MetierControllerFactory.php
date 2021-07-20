<?php

namespace Metier\Controller;

use Application\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Application\Service\Niveau\NiveauService;
use Application\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Interop\Container\ContainerInterface;
use Metier\Form\Metier\MetierForm;
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
         * @var NiveauService $niveauService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         * @var ReferentielService $referentielService
         */
        $domaineService = $container->get(DomaineService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $metierService = $container->get(MetierService::class);
        $niveauService = $container->get(NiveauService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var MetierForm $metierForm
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         */
        $metierForm = $container->get('FormElementManager')->get(MetierForm::class);
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);

        /** @var MetierController $controller */
        $controller = new MetierController();
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setMetierService($metierService);
        $controller->setNiveauService($niveauService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setReferentielService($referentielService);
        $controller->setMetierForm($metierForm);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);

        return $controller;
    }
}
