<?php

namespace Formation\Form\Demande2Formation;

use Formation\Entity\Db\DemandeExterne;
use Laminas\Hydrator\HydratorInterface;
use Psr\Container\ContainerInterface;

class Demande2FormationHydratorFactory {

    public function __invoke(ContainerInterface $container) : Demande2FormationHydrator
    {
        $hydrator = new Demande2FormationHydrator();
        return $hydrator;
    }
}