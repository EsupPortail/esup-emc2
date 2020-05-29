<?php


namespace Application\Form\EntretienProfessionnelCampagne;

use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Interop\Container\ContainerInterface;

class EntretienProfessionnelCampagneHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelCampagneHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntretienProfessionnelCampagneService $campagneService
         */
        $campagneService = $container->get(EntretienProfessionnelCampagneService::class);

        /** @var EntretienProfessionnelCampagneHydrator $hydrator */
        $hydrator = new EntretienProfessionnelCampagneHydrator();
        $hydrator->setEntretienProfessionnelCampagneService($campagneService);
        return $hydrator;
    }
}