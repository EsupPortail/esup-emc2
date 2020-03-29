<?php

namespace Application\Controller;

use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\MetierForm;
use Application\Service\Domaine\DomaineService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;

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
         */
        $domaineService = $container->get(DomaineService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $metierService = $container->get(MetierService::class);

        /**
         * @var DomaineForm $domaineForm
         * @var FamilleProfessionnelleForm $familleForm
         * @var MetierForm $metierForm
         */
        $domaineForm = $container->get('FormElementManager')->get(DomaineForm::class);
        $familleForm = $container->get('FormElementManager')->get(FamilleProfessionnelleForm::class);
        $metierForm = $container->get('FormElementManager')->get(MetierForm::class);

        /** @var MetierController $controller */
        $controller = new MetierController();

        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setMetierService($metierService);

        $controller->setDomaineForm($domaineForm);
        $controller->setFamilleProfessionnelleForm($familleForm);
        $controller->setMetierForm($metierForm);

        return $controller;
    }
}
