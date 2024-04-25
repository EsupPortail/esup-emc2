<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Observateur\ObservateurForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observateur\ObservateurService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservateurControllerFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurController
    {
        /**
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var ObservateurService $observateurService
         * @var ObservateurForm $observateurForm
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $observateurService = $container->get(ObservateurService::class);
        $observateurForm = $container->get('FormElementManager')->get(ObservateurForm::class);

        $controller = new ObservateurController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setObservateurService($observateurService);
        $controller->setObservateurForm($observateurForm);
        return $controller;
    }
}