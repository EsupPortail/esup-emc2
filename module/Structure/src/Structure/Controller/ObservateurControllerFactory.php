<?php

namespace Structure\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Form\Observateur\ObservateurForm;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class ObservateurControllerFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurController
    {
        /**
         * @var ObservateurService $observateurService
         * @var StructureService $structureService
         * @var UserService $userService
         * @var ObservateurForm $observateurForm
         */
        $observateurService = $container->get(ObservateurService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);
        $observateurForm = $container->get('FormElementManager')->get(ObservateurForm::class);

        $controller = new ObservateurController();
        $controller->setObservateurForm($observateurForm);
        $controller->setStructureService($structureService);
        $controller->setObservateurService($observateurService);
        $controller->setUserService($userService);
        return $controller;
    }
}