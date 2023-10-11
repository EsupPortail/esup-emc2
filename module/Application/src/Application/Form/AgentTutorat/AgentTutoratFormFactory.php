<?php

namespace Application\Form\AgentTutorat;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentTutoratFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratForm
    {
        /**
         * @var AgentService $agentService
         * @var MetierService $metierService
         */
        $agentService = $container->get(AgentService::class);
        $metierService = $container->get(MetierService::class);

        /** @var AgentTutoratHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentTutoratHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);

        $form = new AgentTutoratForm();
        $form->urlAgent = $urlAgent;
        $form->setAgentService($agentService);
        $form->setMetierService($metierService);
        $form->setHydrator($hydrator);
        return $form;
    }
}