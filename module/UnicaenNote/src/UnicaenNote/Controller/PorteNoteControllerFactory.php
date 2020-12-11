<?php

namespace UnicaenNote\Controller;

use Interop\Container\ContainerInterface;
use UnicaenNote\Form\PorteNote\PorteNoteForm;
use UnicaenNote\Service\PorteNote\PorteNoteService;

class PorteNoteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    public function __invoke(ContainerInterface  $container)
    {
        /**
         * @var PorteNoteService $porteNoteService
         */
        $porteNoteService = $container->get(PorteNoteService::class);

        /**
         * @var PorteNoteForm $porteNoteForm
         */
        $porteNoteForm = $container->get('FormElementManager')->get(PorteNoteForm::class);

        $controller = new PorteNoteController();
        $controller->setPorteNoteService($porteNoteService);
        $controller->setPorteNoteForm($porteNoteForm);
        return $controller;
    }
}