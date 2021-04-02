<?php

namespace Application\Form\MaitriseNiveau;

use Interop\Container\ContainerInterface;

class MaitriseNiveauHydratorFactory {


    /**
     * @param ContainerInterface $container
     * @return MaitriseNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new MaitriseNiveauHydrator();
        return $hydrator;
    }
}