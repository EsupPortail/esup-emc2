<?php

namespace Metier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Interop\Container\ContainerInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FamilleProfessionnelleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FamilleProfessionnelleController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FamilleProfessionnelleController
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