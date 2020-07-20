<?php

namespace Application\Assertion;

use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelAssertion
     */
    public function  __invoke(ContainerInterface $container)
    {
        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        /** @var EntretienProfessionnelAssertion $assertion */
        $assertion = new EntretienProfessionnelAssertion();
        $assertion->setUserService($userService);

        return $assertion;
    }
}