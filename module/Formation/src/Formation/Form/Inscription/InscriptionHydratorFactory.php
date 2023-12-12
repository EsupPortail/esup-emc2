<?php

namespace Formation\Form\Inscription;

use Application\Service\Agent\AgentService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return InscriptionHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionHydrator
    {
        /**
         * @var FormationInstanceService $sessionService
         * @var AgentService $agentService
         * @var StagiaireExterneService $stagiaireService
         */
        $agentService = $container->get(AgentService::class);
        $sessionService = $container->get(FormationInstanceService::class);
        $stagiaireService = $container->get(StagiaireExterneService::class);

        $hydrator = new InscriptionHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setFormationInstanceService($sessionService);
        $hydrator->setStagiaireExterneService($stagiaireService);
        return $hydrator;
    }
}