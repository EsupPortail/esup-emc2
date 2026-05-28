<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use Agent\Service\Agent\AgentService;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Controller\ContactController;
use Structure\Service\Structure\StructureService;

class AgentForceSansObligationFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentForceSansObligationForm
    {
        /**
         * @var CampagneService $campagneService
         * @var AgentService $agentService
         * @var AgentForceSansObligationService $agentForceSansObligationService
         * @var StructureService $structureService
         * @var AgentForceSansObligationHydrator $hydrator
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $structureService = $container->get(StructureService::class);
        $agentForceSansObligationService = $container->get(AgentForceSansObligationService::class);
        $hydrator = $container->get('HydratorManager')->get(AgentForceSansObligationHydrator::class);

        /**
         * @var HelperPluginManager $pluginManager
         * @var Url $urlManager
         */
        $pluginManager = $container->get('ViewHelperManager');
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);
        /** @see ContactController::rechercherAction() */
        $urlStructure =  $urlManager->__invoke('structure/rechercher', [], [], true);

        $form = new AgentForceSansObligationForm();
        $form->setAgentService($agentService);
        $form->setCampagneService($campagneService);
        $form->setStructureService($structureService);
        $form->setAgentForceSansObligationService($agentForceSansObligationService);
        $form->setUrlAgent($urlAgent);
        $form->setUrlStructure($urlStructure);
        $form->setHydrator($hydrator);
        return $form;
    }
}
