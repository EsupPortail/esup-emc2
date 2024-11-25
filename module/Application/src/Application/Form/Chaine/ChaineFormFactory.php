<?php

namespace Application\Form\Chaine;

use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChaineFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ChaineForm
    {
        /**
         * @var ChaineHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ChaineHydrator::class);


        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent       =  $urlManager->__invoke('agent/rechercher-large', [], [], true);

        $form = new ChaineForm();
        $form->setUrlAgent($urlAgent);
        $form->setHydrator($hydrator);
        return $form;
    }
}