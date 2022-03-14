<?php

namespace Formation\Assertion;

use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritAssertionFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var FormationInstanceInscritAssertion $assertion */
        $assertion = new FormationInstanceInscritAssertion();
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        return $assertion;
    }
}