<?php

namespace Application\Form\AgentTutorat;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use UnicaenEtat\Service\Etat\EtatService;

class AgentTutoratHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratHydrator
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratHydrator
    {
        /**
         * @var AgentService $agentService
         * @var MetierService $metierService
         * @var EtatService $etatService
         */
        $agentService = $container->get(AgentService::class);
        $metierService = $container->get(MetierService::class);
        $etatService = $container->get(EtatService::class);

        $hydrator = new AgentTutoratHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setMetierService($metierService);
        $hydrator->setEtatService($etatService);
        return $hydrator;
    }
}