<?php

namespace Application\Form\AgentAccompagnement;

use Application\Service\Agent\AgentService;
use Carriere\Service\Corps\CorpsService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class AgentAccompagnementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementForm
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementForm
    {
        /**
         * @var AgentService $agentService
         * @var CorrespondanceService $correspondanceService
         * @var CorpsService $corpsService
         * @var EtatService $etatService
         */
        $agentService = $container->get(AgentService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $corpsService = $container->get(CorpsService::class);
        $etatService = $container->get(EtatService::class);

        /** @var AgentAccompagnementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentAccompagnementHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);

        $form = new AgentAccompagnementForm();
        $form->urlAgent = $urlAgent;
        $form->setAgentService($agentService);
        $form->setCorpsService($corpsService);
        $form->setCorrespondanceService($correspondanceService);
        $form->setEtatService($etatService);
        $form->setHydrator($hydrator);
        return $form;
    }
}