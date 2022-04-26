<?php

namespace EntretienProfessionnel\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\Complement\ComplementService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;
use Zend\Mvc\Application;

class EntretienProfessionnelAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelAssertion
     */
    public function  __invoke(ContainerInterface $container) : EntretienProfessionnelAssertion
    {
        /**
         * @var AgentService $agentService
         * @var ComplementService $complementService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $complementService = $container->get(ComplementService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var EntretienProfessionnelAssertion $assertion */
        $assertion = new EntretienProfessionnelAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setComplementService($complementService);
        $assertion->setEntretienProfessionnelService($entretienProfessionnelService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}