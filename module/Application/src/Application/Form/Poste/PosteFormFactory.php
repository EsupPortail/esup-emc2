<?php

namespace Application\Form\Poste;

use Application\Controller\StructureController;
use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\View\HelperPluginManager;

class PosteFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var StructureService $structureService
         * @var RessourceRhService $ressourceService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService  = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);
        $ressourceService  = $container->get(RessourceRhService::class);

        /** @var PosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(PosteHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var \Zend\View\Helper\Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see StructureController::rechercherAction() */
        $urlStructure =  $urlManager->__invoke('structure/rechercher', [], [], true);
        /** @see AgentController::rechercherAction() */
        $urlAgent     =  $urlManager->__invoke('agent/rechercher', [], [], true);


        $form = new PosteForm();
        $form->setEntityManager($entityManager);
        $form->setAgentService($agentService);
        $form->setStructureService($structureService);
        $form->setRessourceRhService($ressourceService);
        $form->setUrlStructure($urlStructure);
        $form->setUrlRattachement($urlAgent);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}
