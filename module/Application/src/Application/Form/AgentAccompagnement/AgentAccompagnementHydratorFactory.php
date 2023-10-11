<?php

namespace Application\Form\AgentAccompagnement;

use Application\Service\Agent\AgentService;
use Carriere\Service\Corps\CorpsService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentAccompagnementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementHydrator
    {
        /**
         * @var AgentService $agentService
         * @var CorrespondanceService $correspondanceService
         * @var CorpsService $corpsService
         * @var EtatTypeService $etatTypeService
         */
        $agentService = $container->get(AgentService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $corpsService = $container->get(CorpsService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $hydrator = new AgentAccompagnementHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setCorrespondanceService($correspondanceService);
        $hydrator->setCorpsService($corpsService);
        $hydrator->setEtatTypeService($etatTypeService);
        return $hydrator;
    }
}