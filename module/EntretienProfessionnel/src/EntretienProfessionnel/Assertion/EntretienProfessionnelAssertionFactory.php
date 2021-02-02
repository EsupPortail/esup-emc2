<?php

namespace EntretienProfessionnel\Assertion;

use Application\Service\Structure\StructureService;
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
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var EntretienProfessionnelAssertion $assertion */
        $assertion = new EntretienProfessionnelAssertion();
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        return $assertion;
    }
}