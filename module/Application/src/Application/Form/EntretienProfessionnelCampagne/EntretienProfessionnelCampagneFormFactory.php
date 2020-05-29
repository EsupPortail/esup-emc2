<?php

namespace Application\Form\EntretienProfessionnelCampagne;

use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Interop\Container\ContainerInterface;

class EntretienProfessionnelCampagneFormFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelCampagneForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntretienProfessionnelCampagneService $campagneService
         * @var EntretienProfessionnelCampagneHydrator $hydrator
         */
        $campagneService = $container->get(EntretienProfessionnelCampagneService::class);
        $hydrator = $container->get('HydratorManager')->get(EntretienProfessionnelCampagneHydrator::class);

        /** @var EntretienProfessionnelCampagneForm $form */
        $form = new EntretienProfessionnelCampagneForm();
        $form->setEntretienProfessionnelCampagneService($campagneService);
        $form->setHydrator($hydrator);
        return $form;
    }
}