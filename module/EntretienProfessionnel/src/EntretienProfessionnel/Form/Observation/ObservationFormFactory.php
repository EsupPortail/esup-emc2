<?php

namespace EntretienProfessionnel\Form\Observation;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ObservationForm
    {
        /**
         * @var ObservationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ObservationHydrator::class);

        $form = new ObservationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}