<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

use Application\Controller\AgentController;
use EntretienProfessionnel\Controller\EntretienProfessionnelController;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EntretienProfessionnelFormFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EntretienProfessionnelForm
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

        $form = new EntretienProfessionnelForm();
        $form->setCampagneService($campagneService);
        $form->setUrlAgent($urlAgent);
//        $form->setUrlResponsable($urlReponsable);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}
