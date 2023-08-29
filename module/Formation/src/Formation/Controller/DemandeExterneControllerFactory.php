<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Formation\Form\Demande2Formation\Demande2FormationForm;
use Formation\Form\DemandeExterne\DemandeExterneForm;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class DemandeExterneControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DemandeExterneController
    {
        /**
         * @var AgentService $agentService
         * @var DemandeExterneService $demandeExterneService
         * @var EtatInstanceService $etatInstanceService
         * @var EtatTypeService $etatTypeService
         * @var FichierService $fichierService
         * @var NatureService $natureService
         * @var NotificationService $notificationService
         */
        $agentService = $container->get(AgentService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $fichierService = $container->get(FichierService::class);
        $natureService = $container->get(NatureService::class);
        $notificationService = $container->get(NotificationService::class);

        /**
         * @var DemandeExterneForm $demandeExterneForm
         * @var Demande2FormationForm $demande2formationForm
         * @var InscriptionForm $inscriptionForm
         * @var UploadForm $uploadForm
         */
        $demandeExterneForm = $container->get('FormElementManager')->get(DemandeExterneForm::class);
        $demande2formationForm = $container->get('FormElementManager')->get(Demande2FormationForm::class);
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);


        $controller = new DemandeExterneController();
        $controller->setAgentService($agentService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFichierService($fichierService);
        $controller->setNatureService($natureService);
        $controller->setNotificationService($notificationService);

        $controller->setDemandeExterneForm($demandeExterneForm);
        $controller->setDemande2formationForm($demande2formationForm);
        $controller->setInscriptionForm($inscriptionForm);
        $controller->setUploadForm($uploadForm);

        return $controller;
    }
}