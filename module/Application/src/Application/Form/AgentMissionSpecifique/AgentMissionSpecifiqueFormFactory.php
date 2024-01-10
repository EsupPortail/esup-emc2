<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Controller\AgentController;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentMissionSpecifiqueFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMissionSpecifiqueForm
    {

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
        $urlAgent = $urlManager->__invoke('agent/rechercher', [], [], true);
        /** @see StructureController::rechercherAction() */
        $urlStructure = $urlManager->__invoke('structure/rechercher', [], [], true);

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