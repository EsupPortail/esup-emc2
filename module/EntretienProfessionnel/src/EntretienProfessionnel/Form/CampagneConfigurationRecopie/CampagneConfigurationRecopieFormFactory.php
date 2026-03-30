<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationRecopie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Champ\ChampService;
use UnicaenAutoform\Service\Formulaire\FormulaireService;

class CampagneConfigurationRecopieFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationRecopieForm
    {
        /**
         * @var ChampService $champService
         * @var FormulaireService $formulaireService
         * @var CampagneConfigurationRecopieHydrator $hydrator
         */
        $champService = $container->get(ChampService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $hydrator = $container->get('HydratorManager')->get(CampagneConfigurationRecopieHydrator::class);

        $form = new CampagneConfigurationRecopieForm();
        $form->setHydrator($hydrator);
        $form->setChampService($champService);
        $form->setFormulaireService($formulaireService);
        return $form;
    }
}