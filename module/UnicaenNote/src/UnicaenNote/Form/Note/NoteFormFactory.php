<?php

namespace UnicaenNote\Form\Note;

use Interop\Container\ContainerInterface;
use UnicaenNote\Service\PorteNote\PorteNoteService;
use UnicaenNote\Service\Type\TypeService;

class NoteFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NoteForm
     */
    public function __invoke(ContainerInterface  $container)
    {
        /**
         * @var PorteNoteService $porteNoteService
         * @var TypeService $typeService
         */
        $porteNoteService = $container->get(PorteNoteService::class);
        $typeService = $container->get(TypeService::class);

        /**
         * @var NoteHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(NoteHydrator::class);

        $form = new NoteForm();
        $form->setPorteNoteService($porteNoteService);
        $form->setTypeService($typeService);
        $form->setHydrator($hydrator);
        return $form;

    }
}