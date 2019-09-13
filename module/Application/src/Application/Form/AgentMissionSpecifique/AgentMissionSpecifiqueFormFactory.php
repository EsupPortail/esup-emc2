<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;

class AgentMissionSpecifiqueFormFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var AgentService        $agentService
         * @var RessourceRhService  $ressourceService
         * @var StructureService    $structureService
         */
        $agentService = $container->get(AgentService::class);
        $ressourceService = $container->get(RessourceRhService::class);
        $structureService = $container->get(StructureService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentMissionSpecifiqueHydrator::class);

        /** @var AgentMissionSpecifiqueForm $form */
        $form = new AgentMissionSpecifiqueForm();
        $form->setHydrator($hydrator);
        $form->setAgentService($agentService);
        $form->setRessourceRhService($ressourceService);
        $form->setStructureService($structureService);
        //$form->init();
        return $form;
    }
}