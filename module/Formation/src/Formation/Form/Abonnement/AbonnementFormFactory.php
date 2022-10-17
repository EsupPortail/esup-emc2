<?php

namespace Formation\Form\Abonnement;

use Formation\Service\Formation\FormationService;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbonnementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AbonnementForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AbonnementForm
    {
        /**
         * @see FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);

        /** @var AbonnementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AbonnementHydrator::class);

        $form = new AbonnementForm();
        $form->setFormationService($formationService);
        $form->setUrlAgent($urlAgent);
        $form->setHydrator($hydrator);
        return $form;

    }
}