<?php

namespace UnicaenNote\Form\Note;

use Interop\Container\ContainerInterface;
use UnicaenNote\Service\PorteNote\PorteNoteService;
use UnicaenNote\Service\Type\TypeService;

class NoteHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return NoteHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var PorteNoteService $porteNoteService
         * @var TypeService $typeService
         */
        $porteNoteService = $container->get(PorteNoteService::class);
        $typeService = $container->get(TypeService::class);

        $hydrator = new NoteHydrator();
        $hydrator->setPorteNoteService($porteNoteService);
        $hydrator->setTypeService($typeService);
        return $hydrator;
    }
}