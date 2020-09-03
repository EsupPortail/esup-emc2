<?php

namespace Application\Form\SelectionAgent;

use Application\Controller\AgentController;
use Interop\Container\ContainerInterface;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class SelectionAgentFormFactory {

    public function __invoke(ContainerInterface $container) {


        /** @var SelectionAgentHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SelectionAgentHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);

        /** @var SelectionAgentForm $form */
        $form = new SelectionAgentForm();
        $form->setHydrator($hydrator);
        $form->setUrlAgent($urlAgent);
        return $form;
    }
}