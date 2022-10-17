<?php

namespace Application\Form\AgentTutorat;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use UnicaenEtat\Service\Etat\EtatService;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;

class AgentTutoratFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratForm
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratForm
    {
        /**
         * @var AgentService $agentService
         * @var MetierService $metierService
         * @var EtatService $etatService
         */
        $agentService = $container->get(AgentService::class);
        $metierService = $container->get(MetierService::class);
        $etatService = $container->get(EtatService::class);

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
        $form->setEtatService($etatService);
        $form->setHydrator($hydrator);
        return $form;
    }
}