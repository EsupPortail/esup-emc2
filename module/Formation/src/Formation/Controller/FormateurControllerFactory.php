<?php

namespace Formation\Controller;

use Formation\Form\Formateur\FormateurForm;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Form\User\UserForm;
use UnicaenUtilisateur\Form\User\UserRechercheForm;
use UnicaenUtilisateur\Service\User\UserService;

class FormateurControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormateurController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormateurController
    {
        /**
         * @var SessionService $sessionService
         * @var FormateurService $formateurService
         * @var UserService $userService
         */
        $sessionService = $container->get(SessionService::class);
        $formateurService = $container->get(FormateurService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var FormateurForm $formateurForm
         * @var UserForm $userForm
         * @var UserRechercheForm $userRechercheForm
         */
        $formateurForm = $container->get('FormElementManager')->get(FormateurForm::class);
        $userForm = $container->get('FormElementManager')->get(UserForm::class);
        $userRechercheForm = $container->get('FormElementManager')->get(UserRechercheForm::class);

        $controller = new FormateurController();
        $controller->setSessionService($sessionService);
        $controller->setFormateurService($formateurService);
        $controller->setUserService($userService);
        $controller->setFormateurForm($formateurForm);
        $controller->setUserForm($userForm);
        $controller->setUserRechercheForm($userRechercheForm);
        return $controller;
    }
}