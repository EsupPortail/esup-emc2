<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationPresaisie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Champ\ChampService;
use UnicaenAutoform\Service\Formulaire\FormulaireService;
use UnicaenRenderer\Service\Macro\MacroService;

class CampagneConfigurationPresaisieFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationPresaisieForm
    {
        /**
         * @var ChampService $champService
         * @var FormulaireService $formulaireService
         * @var MacroService $macroService
         * @var CampagneConfigurationPresaisieHydrator $hydrator
         */
        $champService = $container->get(ChampService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $macroService = $container->get(MacroService::class);
        $hydrator = $container->get('HydratorManager')->get(CampagneConfigurationPresaisieHydrator::class);

        $form = new CampagneConfigurationPresaisieForm();
        $form->setHydrator($hydrator);
        $form->setChampService($champService);
        $form->setFormulaireService($formulaireService);
        $form->setMacroService($macroService);
        return $form;
    }
}