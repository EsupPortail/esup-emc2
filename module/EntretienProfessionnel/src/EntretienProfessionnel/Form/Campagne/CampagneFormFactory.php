<?php

namespace EntretienProfessionnel\Form\Campagne;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;

class CampagneFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneForm
     */
    public function __invoke(ContainerInterface $container) : CampagneForm
    {
        /**
         * @var CampagneService $campagneService
         * @var CampagneHydrator $hydrator
         */
        $campagneService = $container->get(CampagneService::class);
        $hydrator = $container->get('HydratorManager')->get(CampagneHydrator::class);

        $form = new CampagneForm();
        $form->setCampagneService($campagneService);
        $form->setHydrator($hydrator);
        return $form;
    }
}