<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FichesPostesAsArrayViewHelperFactory {

    public function __invoke(ContainerInterface $container) : FichesPostesAsArrayViewHelper
    {
        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        $helper = new FichesPostesAsArrayViewHelper();
        $helper->setUserService($userService);
        return $helper;
    }
}