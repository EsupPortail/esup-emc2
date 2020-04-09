<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Controller\AgentController;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class AgentMissionSpecifiqueFormFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var MissionSpecifiqueService $missionSpecifiqueService
         */
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentMissionSpecifiqueHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);
        /** @see StructureController::rechercherAction() */
        $urlStructure =  $urlManager->__invoke('structure/rechercher', [], [], true);

        /** @var AgentMissionSpecifiqueForm $form */
        $form = new AgentMissionSpecifiqueForm();
        $form->setHydrator($hydrator);
        $form->setMissionSpecifiqueService($missionSpecifiqueService);
        $form->setUrlAgent($urlAgent);
        $form->setUrlStructure($urlStructure);
        //$form->init();
        return $form;
    }
}