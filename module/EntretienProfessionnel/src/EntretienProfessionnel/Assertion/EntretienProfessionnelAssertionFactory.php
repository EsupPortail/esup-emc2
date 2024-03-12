<?php

namespace EntretienProfessionnel\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EntretienProfessionnelAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var StructureService $structureService
         * @var ParametreService $parametreService
         * @var PrivilegeService $privilegeService
         * @var UserService $userService
         **/
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $privilegeService = $container->get(PrivilegeService::class);

        $assertion = new EntretienProfessionnelAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setEntretienProfessionnelService($entretienProfessionnelService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        $assertion->setPrivilegeService($privilegeService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);

        $assertion->setBLOCAGECOMPTERENDU($parametreService->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::CAMPAGNE_BLOCAGE_STRICT_MODIFICATION));
        $assertion->setBLOCAGEVALIDATION($parametreService->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::CAMPAGNE_BLOCAGE_STRICT_VALIDATION));

        return $assertion;
    }
}