<?php

namespace Formation\Controller;

use Formation\Event\Convocation\ConvocationEvent;
use Formation\Event\DemandeRetour\DemandeRetourEvent;
use Formation\Event\InscriptionCloture\InscriptionClotureEvent;
use Formation\Event\RappelAgent\RappelAgentEvent;
use Formation\Event\SessionCloture\SessionClotureEvent;
use Formation\Form\Seance\SeanceForm;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEvenement\Service\Evenement\EvenementService;

class SeanceControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SeanceController
    {
        /**
         * @var EvenementService $evenementService
         * @var SeanceService $seanceService
         * @var SessionService $sessionService
         */
        $evenementService = $container->get(EvenementService::class);
        $seanceService = $container->get(SeanceService::class);
        $sessionService = $container->get(SessionService::class);

        /**
         * @var ConvocationEvent $convocationEvent
         * @var DemandeRetourEvent $demandeRetourEvent
         * @var InscriptionClotureEvent $inscriptionClotureEvent
         * @var RappelAgentEvent $rappelAgentEvent
         * @var SessionClotureEvent $sessionClotureEvent
         */
        $convocationEvent = $container->get(ConvocationEvent::class);
        $demandeRetourEvent = $container->get(DemandeRetourEvent::class);
        $inscriptionClotureEvent = $container->get(InscriptionClotureEvent::class);
        $rappelAgentEvent = $container->get(RappelAgentEvent::class);
        $sessionClotureEvent = $container->get(SessionClotureEvent::class);

        /**
         * @var SeanceForm $seanceForm
         */
        $seanceForm = $container->get('FormElementManager')->get(SeanceForm::class);

        $controller = new SeanceController();
        $controller->setEvenementService($evenementService);
        $controller->setSeanceService($seanceService);
        $controller->setSessionService($sessionService);

        $controller->setConvocationEvent($convocationEvent);
        $controller->setDemandeRetourEvent($demandeRetourEvent);
        $controller->setInscriptionClotureEvent($inscriptionClotureEvent);
        $controller->setRappelAgentEvent($rappelAgentEvent);
        $controller->setSessionClotureEvent($sessionClotureEvent);

        $controller->setSeanceForm($seanceForm);
        return $controller;
    }
}