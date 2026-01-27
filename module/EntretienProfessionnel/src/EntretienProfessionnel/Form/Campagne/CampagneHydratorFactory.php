<?php


namespace EntretienProfessionnel\Form\Campagne;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireService;

class CampagneHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CampagneHydrator
    {
        /**
         * @var CampagneService $campagneService
         * @var FormulaireService $formulaireService
         */
        $campagneService = $container->get(CampagneService::class);
        $formulaireService = $container->get(FormulaireService::class);

        $hydrator = new CampagneHydrator();
        $hydrator->setCampagneService($campagneService);
        $hydrator->setFormulaireService($formulaireService);
        return $hydrator;
    }
}