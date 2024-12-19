<?php

namespace Agent\Form\AgentMobilite;

use Application\Service\Agent\AgentService;
use Carriere\Service\Mobilite\MobiliteService;
use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentMobiliteFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentMobiliteForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMobiliteForm
    {
        /**
         * @var AgentService $agentService
         * @var MobiliteService $mobiliteService
         */
        $agentService = $container->get(AgentService::class);
        $mobiliteService = $container->get(MobiliteService::class);

        /** @var AgentMobiliteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentMobiliteHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent = $urlManager->__invoke('agent/rechercher', [], [], true);

        $form = new AgentMobiliteForm();
        $form->urlAgent = $urlAgent;
        $form->setAgentService($agentService);
        $form->setMobiliteService($mobiliteService);
        $form->setHydrator($hydrator);
        return $form;
    }
}