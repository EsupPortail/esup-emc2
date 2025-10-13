<?php

namespace EntretienProfessionnel\Form\Observateur;

use Doctrine\ORM\EntityManager;
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
        $urlRecherche =  $urlManager->__invoke('unicaen-utilisateur/rechercher-interne', [], [], true);
        /** @see EntretienProfessionnelController::rechercherEntretienAction() */
        $urlEntretien = $urlManager->__invoke('entretien-professionnel/rechercher-entretien', [], [], true);

        /** @var ObservateurHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ObservateurHydrator::class);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $form = new ObservateurForm();
        $form->setObjectManager($entityManager);
        $form->setHydrator($hydrator);
        $form->setUrlUser($urlRecherche);
        $form->setUrlEntretien($urlEntretien);
        return $form;
    }
}