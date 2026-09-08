<?php

namespace Structure\Form\Responsabilite;

use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ResponsabiliteFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ResponsabiliteForm
    {
        /**
         * @var ResponsabiliteHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ResponsabiliteHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see \Agent\Controller\AgentController::rechercherLargeAction() */
        $urlAgent       =  $urlManager->__invoke('agent/rechercher-large', [], [], true);
        /** @see \Structure\Controller\StructureController::rechercherAction() */
        $urlStructure       =  $urlManager->__invoke('structure/rechercher', [], [], true);

        $form = new ResponsabiliteForm();
        $form->setUrlAgent($urlAgent);
        $form->setUrlStructure($urlStructure);
        $form->setHydrator($hydrator);
        return $form;
    }
}