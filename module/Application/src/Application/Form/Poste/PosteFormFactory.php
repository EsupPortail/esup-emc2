<?php

namespace Application\Form\Poste;

use Application\Controller\StructureController;
use Application\Service\Agent\AgentService;
use Application\Service\Structure\StructureService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class PosteFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var CorrespondanceService $correspondanceService
         * @var DomaineService $domaineService
         * @var StructureService $structureService
         */
        $agentService  = $container->get(AgentService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $domaineService = $container->get(DomaineService::class);
        $structureService = $container->get(StructureService::class);

        /** @var PosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(PosteHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see StructureController::rechercherAction() */
        $urlStructure =  $urlManager->__invoke('structure/rechercher', [], [], true);
        /** @see AgentController::rechercherAction() */
        $urlAgent     =  $urlManager->__invoke('agent/rechercher', [], [], true);


        $form = new PosteForm();
        $form->setAgentService($agentService);
        $form->setCorrespondanceService($correspondanceService);
        $form->setDomaineService($domaineService);
        $form->setStructureService($structureService);
        $form->setUrlStructure($urlStructure);
        $form->setUrlRattachement($urlAgent);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}
