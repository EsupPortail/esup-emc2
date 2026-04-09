<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationPresaisie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Champ\ChampService;
use UnicaenAutoform\Service\Formulaire\FormulaireService;
use UnicaenRenderer\Service\Macro\MacroService;

class CampagneConfigurationPresaisieHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationPresaisieHydrator
    {
        /**
         * @var ChampService $champService
         * @var FormulaireService $formulaireService
         * @var MacroService $macroService
         */
        $champService = $container->get(ChampService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $macroService = $container->get(MacroService::class);

        $hydrator = new CampagneConfigurationPresaisieHydrator();
        $hydrator->setChampService($champService);
        $hydrator->setFormulaireService($formulaireService);
        $hydrator->setMacroService($macroService);
        return $hydrator;
    }
}