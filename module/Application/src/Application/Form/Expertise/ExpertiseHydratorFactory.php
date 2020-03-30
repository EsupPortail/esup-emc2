<?php

namespace Application\Form\Expertise;

use Interop\Container\ContainerInterface;

class ExpertiseHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ExpertiseHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var  ExpertiseHydrator $hydrator*/
        $hydrator = new ExpertiseHydrator();
        return $hydrator;
    }
}
