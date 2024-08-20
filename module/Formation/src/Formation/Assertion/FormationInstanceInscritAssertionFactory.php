<?php

namespace Formation\Assertion;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceInscritAssertion
    {
        /**
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $assertion = new FormationInstanceInscritAssertion();
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        return $assertion;
    }
}