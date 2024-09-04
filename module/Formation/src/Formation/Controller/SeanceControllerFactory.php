<?php

namespace Formation\Controller;

use Formation\Event\InscriptionCloture\InscriptionClotureEvent;
use Formation\Form\Seance\SeanceForm;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEvenement\Service\Evenement\EvenementService;
use UnicaenParametre\Service\Parametre\ParametreService;

class SeanceControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SeanceController
    {
        /**
         * @var ParametreService $parametreService
         * @var SeanceService $seanceService
         * @var SessionService $sessionService
         */
        $parametreService = $container->get(ParametreService::class);
        $seanceService = $container->get(SeanceService::class);
        $sessionService = $container->get(SessionService::class);

        /**
         * @var EvenementService $evenementService
         * @var InscriptionClotureEvent $inscriptionClotureEvent
         */
        $evenementService = $container->get(EvenementService::class);
        $inscriptionClotureEvent = $container->get(InscriptionClotureEvent::class);

        /**
         * @var SeanceForm $seanceForm
         */
        $seanceForm = $container->get('FormElementManager')->get(SeanceForm::class);

        $controller = new SeanceController();
        $controller->setParametreService($parametreService);
        $controller->setSeanceService($seanceService);
        $controller->setSessionService($sessionService);

        $controller->setEvenementService($evenementService);
        $controller->setInscriptionClotureEvent($inscriptionClotureEvent);


        $controller->setSeanceForm($seanceForm);
        return $controller;
    }
}