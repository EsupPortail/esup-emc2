<?php

namespace EntretienProfessionnel\Form\Observateur;

use EntretienProfessionnel\Controller\EntretienProfessionnelController;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Controller\UtilisateurController;

class ObservateurFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurForm
    {
        /**
         * @var HelperPluginManager $pluginManager
         * @var Url $urlManager
         */
        $pluginManager = $container->get('ViewHelperManager');
        $urlManager = $pluginManager->get('Url');
        /** @see UtilisateurController::rechercherAction() */
        $urlRechercheUtilisateur =  $urlManager->__invoke('unicaen-utilisateur/rechercher-interne', [], [], true);
        /** @see EntretienProfessionnelController::rechercherAction() */
        $urlRechercherEntretien =  $urlManager->__invoke('entretien-professionnel/rechercher', [], [], true);

        /** @var ObservateurHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ObservateurHydrator::class);

        $form = new ObservateurForm();
        $form->setHydrator($hydrator);
        $form->setUrlUser($urlRechercheUtilisateur);
        $form->setUrlEntretienProfessionnel($urlRechercherEntretien);
        return $form;
    }
}