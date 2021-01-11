<?php

namespace Application\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;

class FamilleProfessionnelleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FamilleProfessionnelleController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         */
        $familleService = $container->get(FamilleProfessionnelleService::class);

        /**
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        $controller = new FamilleProfessionnelleController();
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}