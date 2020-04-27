<?php

namespace Application\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\RessourceRh\DomaineForm;
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
         * @var MetierForm $metierForm
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $domaineForm = $container->get('FormElementManager')->get(DomaineForm::class);
        $metierForm = $container->get('FormElementManager')->get(MetierForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        /** @var MetierController $controller */
        $controller = new MetierController();

        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setMetierService($metierService);

        $controller->setDomaineForm($domaineForm);
        $controller->setMetierForm($metierForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);

        return $controller;
    }
}
