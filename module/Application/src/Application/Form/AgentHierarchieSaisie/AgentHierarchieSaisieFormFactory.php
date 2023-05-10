<?php

namespace Application\Form\AgentHierarchieSaisie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;


class AgentHierarchieSaisieFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentHierarchieSaisieForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentHierarchieSaisieForm
    {
        /** @var AgentHierarchieSaisieHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentHierarchieSaisieHydrator::class);

        /** @see \Application\Controller\AgentController::rechercherLargeAction() */
        $pluginManager = $container->get('ViewHelperManager');
        $urlManager = $pluginManager->get('Url');
        $urlAgent =  $urlManager->__invoke('agent/rechercher-large', [], [], true);

        $form = new AgentHierarchieSaisieForm();
        $form->setUrlAgent($urlAgent);
        $form->setHydrator($hydrator);
        return $form;
    }
}