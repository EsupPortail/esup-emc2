<?php

namespace Formation\Form\Seance;

use Formation\Controller\LieuController;
use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SeanceFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return SeanceForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SeanceForm
    {
        /**
         * @var SeanceHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SeanceHydrator::class);

        $form = new SeanceForm();
        $form->setHydrator($hydrator);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see LieuController::rechercherAction() */
        $url =  $urlManager->__invoke('formation/lieu/rechercher', [], [], true);
        $form->setUrl($url);

        return $form;
    }
}