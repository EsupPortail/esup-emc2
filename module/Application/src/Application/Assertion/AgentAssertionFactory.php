<?php

namespace Application\Assertion;

use Application\Service\FichePoste\FichePosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAssertion
     */
    public function  __invoke(ContainerInterface $container)
    {
        /**
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentAssertion $assertion */
        $assertion = new AgentAssertion();
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        return $assertion;
    }
}