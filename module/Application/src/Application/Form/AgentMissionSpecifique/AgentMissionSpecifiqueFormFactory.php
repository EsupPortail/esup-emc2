<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class AgentMissionSpecifiqueFormFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var AgentService        $agentService
         * @var RessourceRhService  $ressourceService
         */
        $agentService = $container->get(AgentService::class);
        $ressourceService = $container->get(RessourceRhService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentMissionSpecifiqueHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see StructureController::rechercherAction() */
        $urlStructure =  $urlManager->__invoke('structure/rechercher', [], [], true);

        /** @var AgentMissionSpecifiqueForm $form */
        $form = new AgentMissionSpecifiqueForm();
        $form->setHydrator($hydrator);
        $form->setAgentService($agentService);
        $form->setRessourceRhService($ressourceService);
        $form->setUrlStructure($urlStructure);
        //$form->init();
        return $form;
    }
}