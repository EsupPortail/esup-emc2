<?php

namespace Application\Controller;

use Application\Entity\Db\AgentAutorite;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentHierarchieControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentHierarchieController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentHierarchieController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);

        /**
         * @var AgentHierarchieImportationForm $importationForm
         */
        $importationForm = $container->get('FormElementManager')->get(AgentHierarchieImportationForm::class);

        $controller = new AgentHierarchieController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentHierarchieImportationForm($importationForm);
        return $controller;
    }

}