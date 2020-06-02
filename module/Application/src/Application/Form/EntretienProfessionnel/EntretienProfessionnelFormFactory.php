<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Controller\AgentController;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Interop\Container\ContainerInterface;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class EntretienProfessionnelFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntretienProfessionnelCampagneService $campagneService
         */
        $campagneService = $container->get(EntretienProfessionnelCampagneService::class);

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
        /** @see AgentController::rechercherResponsableAction() */
        $urlReponsable  =  $urlManager->__invoke('agent/rechercher-responsable', [], [], true);

        /**
         * @var EntretienProfessionnelForm $form
         */
        $form = new EntretienProfessionnelForm();
        $form->setEntretienProfessionnelCampagneService($campagneService);
        $form->setUrlAgent($urlAgent);
        $form->setUrlResponsable($urlReponsable);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}
