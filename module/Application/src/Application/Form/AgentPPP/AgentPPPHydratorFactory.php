<?php

namespace Application\Form\AgentPPP;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class AgentPPPHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPPPHydrator
     */
    public function __invoke(ContainerInterface $container) : AgentPPPHydrator
    {
        /**
         * @var EtatService $etatService
         */
        $etatService = $container->get(EtatService::class);

        $hydrator = new AgentPPPHydrator();
        $hydrator->setEtatService($etatService);
        return $hydrator;
    }
}