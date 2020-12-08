<?php

namespace UnicaenNote\Controller;

use Interop\Container\ContainerInterface;
use UnicaenNote\Form\Note\NoteForm;
use UnicaenNote\Service\Note\NoteService;
use UnicaenNote\Service\PorteNote\PorteNoteService;

class NoteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return NoteController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var PorteNoteService $porteNoteService
         * @var NoteService $noteService
         */
        $noteService = $container->get(NoteService::class);
        $porteNoteService = $container->get(PorteNoteService::class);

        /**
         * @var NoteForm $noteForm
         */
        $noteForm = $container->get('FormElementManager')->get(NoteForm::class);

        $controller = new NoteController();
        $controller->setPorteNoteService($porteNoteService);
        $controller->setNoteService($noteService);
        $controller->setNoteForm($noteForm);
        return $controller;
    }
}