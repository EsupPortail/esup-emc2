<?php

namespace Application\Form\AgentAccompagnement;

use Application\Service\Agent\AgentService;
use Carriere\Service\Corps\CorpsService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class AgentAccompagnementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementHydrator
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementHydrator
    {
        /**
         * @var AgentService $agentService
         * @var CorrespondanceService $correspondanceService
         * @var CorpsService $corpsService
         * @var EtatService $etatService
         */
        $agentService = $container->get(AgentService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $corpsService = $container->get(CorpsService::class);
        $etatService = $container->get(EtatService::class);

        $hydrator = new AgentAccompagnementHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setCorrespondanceService($correspondanceService);
        $hydrator->setCorpsService($corpsService);
        $hydrator->setEtatService($etatService);
        return $hydrator;
    }
}