<?php

namespace Application\Controller;

use Application\Entity\Db\AgentAutorite;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculForm;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

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
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var AgentHierarchieImportationForm $importationForm
         * @var AgentHierarchieCalculForm $calculForm
         */
        $importationForm = $container->get('FormElementManager')->get(AgentHierarchieImportationForm::class);
        $calculForm = $container->get('FormElementManager')->get(AgentHierarchieCalculForm::class);

        $controller = new AgentHierarchieController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setStructureService($structureService);
        $controller->setAgentHierarchieCalculForm($calculForm);
        $controller->setAgentHierarchieImportationForm($importationForm);
        return $controller;
    }

}