<?php

namespace EntretienProfessionnel\Form\Observation;

use Interop\Container\ContainerInterface;

class ObservationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationForm
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