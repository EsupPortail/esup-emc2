<?php

namespace Formation\Assertion;


use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Inscription\InscriptionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureService;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class InscriptionAssertionFactory
{
    use FormationInstanceServiceAwareTrait;
    use PrivilegeCategorieServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var InscriptionService $inscriptionService
         * @var ObservateurService $observateurService
         * @var PrivilegeService $privilegeService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $observateurService = $container->get(ObservateurService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $assertion = new InscriptionAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setInscriptionService($inscriptionService);
        $assertion->setObservateurService($observateurService);
        $assertion->setPrivilegeService($privilegeService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);
        return $assertion;
    }
}