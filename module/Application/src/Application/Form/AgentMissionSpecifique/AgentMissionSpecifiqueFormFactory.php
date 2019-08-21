<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Zend\Form\FormElementManager;

class AgentMissionSpecifiqueFormFactory {

    public function __invoke(FormElementManager $manager) {

        /**
         * @var AgentService        $agentService
         * @var RessourceRhService  $ressourceService
         * @var StructureService    $structureService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);
        $structureService = $manager->getServiceLocator()->get(StructureService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AgentMissionSpecifiqueHydrator::class);

        /** @var AgentMissionSpecifiqueForm $form */
        $form = new AgentMissionSpecifiqueForm();
        $form->setHydrator($hydrator);
        $form->setAgentService($agentService);
        $form->setRessourceRhService($ressourceService);
        $form->setStructureService($structureService);
        $form->init();
        return $form;
    }
}