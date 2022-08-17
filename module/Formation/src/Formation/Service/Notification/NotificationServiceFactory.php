<?php

namespace Formation\Service\Notification;

use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class NotificationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NotificationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NotificationService
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var MailService $mailService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var RoleService $roleService
         * @var UrlService $urlService
         * @var UserService $userService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $roleService = $container->get(RoleService::class);
        $urlService = $container->get(UrlService::class);
        $userService = $container->get(UserService::class);

        $service = new NotificationService();
        $service->setFormationInstanceService($formationInstanceService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setRoleService($roleService);
        $service->setUrlService($urlService);
        $service->setUserService($userService);
        return $service;
    }
}