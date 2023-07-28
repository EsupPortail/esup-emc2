<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenUtilisateur\Service\User\UserService;

class FichesPostesAsArrayViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return FichesPostesAsArrayViewHelper
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FichesPostesAsArrayViewHelper
    {
        /**
         * @var EtatTypeService $etatTypeService
         * @var UserService $userService
         */
        $etatTypeService = $container->get(EtatTypeService::class);
        $userService = $container->get(UserService::class);

        $helper = new FichesPostesAsArrayViewHelper();
        $helper->setEtatTypeService($etatTypeService);
        $helper->setUserService($userService);
        return $helper;
    }
}