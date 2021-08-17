<?php

namespace UnicaenNote\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenNote\Service\PorteNote\PorteNoteService;
use UnicaenPrivilege\Entity\Db\Privilege;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserService;

class PorteNoteViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return PorteNoteViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
//        /** @var PrivilegeService $privilegeService */
//        $privilegeService = $container->get(PrivilegeService::class);
//        $privilegeModifier = $privilegeService->getPrivilege(360);
//        /** @var UserService $userService */
//        $userService = $container->get(UserService::class);
//        $role = $userService->getConnectedRole();
//
//        $canModifier = $privilegeModifier->hasRole($role);


        /**
         * @var PorteNoteService $porteNoteService
         */
        $porteNoteService = $container->get(PorteNoteService::class);

        $helper = new PorteNoteViewHelper();
//        $helper->canModifier = $canModifier;
        $helper->setPorteNoteService($porteNoteService);
        return $helper;
    }
}