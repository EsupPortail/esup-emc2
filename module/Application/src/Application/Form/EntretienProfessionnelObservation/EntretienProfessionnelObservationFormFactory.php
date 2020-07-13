<?php

namespace Application\Form\EntretienProfessionnelObservation;

use Interop\Container\ContainerInterface;

class EntretienProfessionnelObservationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelObservationForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntretienProfessionnelObservationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(EntretienProfessionnelObservationHydrator::class);

        /** @var EntretienProfessionnelObservationForm $form */
        $form = new EntretienProfessionnelObservationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}