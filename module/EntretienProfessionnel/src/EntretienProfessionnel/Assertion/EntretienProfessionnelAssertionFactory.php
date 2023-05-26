<?php

namespace EntretienProfessionnel\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function  __invoke(ContainerInterface $container) : EntretienProfessionnelAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var StructureService $structureService
         * @var UserService $userService
         * @var PrivilegeService $privilegeService
         * @var PrivilegeCategorieService $categorieService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $privilegeService = $container->get(PrivilegeService::class);
        $categorieService = $container->get(PrivilegeCategorieService::class);

        /** @var EntretienProfessionnelAssertion $assertion */
        $assertion = new EntretienProfessionnelAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setEntretienProfessionnelService($entretienProfessionnelService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        $assertion->setPrivilegeService($privilegeService);
        $assertion->setPrivilegeCategorieService($categorieService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}