<?php

namespace Carriere\Form\Mobilite;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MobiliteFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MobiliteForm
    {
        /** @var MobiliteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MobiliteHydrator::class);

        $form = new MobiliteForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}