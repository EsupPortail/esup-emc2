<?php

namespace Application\Form\SelectionAgent;

use Application\Controller\AgentController;
use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionAgentFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionAgentForm
    {
        /** @var SelectionAgentHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SelectionAgentHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent =  $urlManager->__invoke('agent/rechercher', [], [], true);

        $form = new SelectionAgentForm();
        $form->setHydrator($hydrator);
        $form->setUrlAgent($urlAgent);
        return $form;
    }
}