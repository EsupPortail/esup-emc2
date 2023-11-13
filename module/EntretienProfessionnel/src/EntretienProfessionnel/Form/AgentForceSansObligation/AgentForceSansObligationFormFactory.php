<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentForceSansObligationFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentForceSansObligationForm
    {
        /**
         * @var CampagneService $campagneService
         * @var AgentForceSansObligationHydrator $hydrator
         */
        $campagneService = $container->get(CampagneService::class);
        $hydrator = $container->get('HydratorManager')->get(AgentForceSansObligationHydrator::class);

        /**
         * @var HelperPluginManager $pluginManager
         * @var Url $urlManager
         */
        $pluginManager = $container->get('ViewHelperManager');
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);

        $form = new AgentForceSansObligationForm();
        $form->setCampagneService($campagneService);
        $form->setUrlAgent($urlAgent);
        $form->setHydrator($hydrator);
        return $form;
    }
}
