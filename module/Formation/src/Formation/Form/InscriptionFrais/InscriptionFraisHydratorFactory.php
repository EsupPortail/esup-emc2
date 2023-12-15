<?php

namespace Formation\Form\InscriptionFrais;


use Psr\Container\ContainerInterface;

class InscriptionFraisHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return InscriptionFraisHydrator
     */
    public function __invoke(ContainerInterface $container): InscriptionFraisHydrator
    {
        $hydrator = new InscriptionFraisHydrator();
        return $hydrator;
    }
}