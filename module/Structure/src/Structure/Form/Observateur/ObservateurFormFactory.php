<?php

namespace Structure\Form\Observateur;

use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Controller\UtilisateurController;

class ObservateurFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurForm
    {
        /**
         * @var ObservateurHydrator $hydratyor
         */
        $hydratyor = $container->get('HydratorManager')->get(ObservateurHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see UtilisateurController::rechercherInterneAction() */
        $urlUtilisateur = $urlManager->__invoke('unicaen-utilisateur/rechercher-interne', [], [], true);
        /** @see StructureController::rechercherAction() */
        $urlStructure = $urlManager->__invoke('structure/rechercher', [], [], true);

        $form = new ObservateurForm();
        $form->setHydrator($hydratyor);
        $form->setUrlStructure($urlStructure);
        $form->setUrlUtilisateur($urlUtilisateur);
        return $form;

    }
}