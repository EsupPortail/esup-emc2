<?php

namespace Application\Form\AgentPPP;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentPPPHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPPPHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentPPPHydrator
    {
        /**
         * @var EtatTypeService $etatTypeService
         */
        $etatTypeService = $container->get(EtatTypeService::class);

        $hydrator = new AgentPPPHydrator();
        $hydrator->setEtatTypeService($etatTypeService);
        return $hydrator;
    }
}