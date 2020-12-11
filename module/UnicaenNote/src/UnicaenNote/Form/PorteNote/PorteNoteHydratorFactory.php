<?php

namespace UnicaenNote\Form\PorteNote;

use Interop\Container\ContainerInterface;

class PorteNoteHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new PorteNoteHydrator();
        return $hydrator;
    }
}