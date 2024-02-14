<?php

namespace Formation\Form\SelectionFormateur;

use Formation\Controller\FormateurController;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionFormateurFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormateurForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionFormateurForm
    {
        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see FormateurController::rechercherAction() */
        $url =  $urlManager->__invoke('formation/formateur/rechercher', [], [], true);

        /**
         * @var SelectionFormateurHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionFormateurHydrator::class);

        $form = new SelectionFormateurForm();
        $form->setUrlFormateur($url);
        $form->setHydrator($hydrator);
        return $form;
    }
}