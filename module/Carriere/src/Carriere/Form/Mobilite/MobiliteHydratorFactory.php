<?php

namespace Carriere\Form\Mobilite;

use Interop\Container\ContainerInterface;

class MobiliteHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return MobiliteHydrator
     */
    public function __invoke(ContainerInterface $container) : MobiliteHydrator
    {
        /** @var MobiliteHydrator $hydrator */
        $hydrator = new MobiliteHydrator();
        return $hydrator;
    }
}