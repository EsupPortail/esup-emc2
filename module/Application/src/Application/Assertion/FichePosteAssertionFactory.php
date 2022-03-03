<?php

namespace Application\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FichePosteAssertionFactory
{
    /**
     * @param ContainerInterface $container
     * @return FichePosteAssertion
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FichePosteService $fichePosteService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $userService = $container->get(UserService::class);

        /** @var FichePosteAssertion $assertion */
        $assertion = new FichePosteAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setFichePosteService($fichePosteService);
        $assertion->setUserService($userService);
        return $assertion;
    }

}