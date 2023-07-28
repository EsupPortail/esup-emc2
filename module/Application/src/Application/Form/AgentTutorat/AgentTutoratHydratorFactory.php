<?php

namespace Application\Form\AgentTutorat;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentTutoratHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratHydrator
    {
        /**
         * @var AgentService $agentService
         * @var EtatTypeService $etatTypeService
         * @var MetierService $metierService
         */
        $agentService = $container->get(AgentService::class);
        $metierService = $container->get(MetierService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $hydrator = new AgentTutoratHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setMetierService($metierService);
        $hydrator->setEtatTypeService($etatTypeService);
        return $hydrator;
    }
}