<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

use Application\Controller\AgentController;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class EntretienProfessionnelFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CampagneService $campagneService
         */
        $campagneService = $container->get(CampagneService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(EntretienProfessionnelHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent       =  $urlManager->__invoke('agent/rechercher', [], [], true);
        /** @see AgentController::rechercherGestionnaireAction() */
        $urlReponsable  =  $urlManager->__invoke('agent/rechercher-gestionnaire', [], [], true);

        /**
         * @var EntretienProfessionnelForm $form
         */
        $form = new EntretienProfessionnelForm();
        $form->setCampagneService($campagneService);
        $form->setUrlAgent($urlAgent);
        $form->setUrlResponsable($urlReponsable);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}
