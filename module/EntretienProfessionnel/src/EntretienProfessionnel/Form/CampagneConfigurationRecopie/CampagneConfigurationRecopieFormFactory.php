<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationRecopie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
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
         * @var FormulaireService $formulaireService
         * @var CampagneConfigurationRecopieHydrator $hydrator
         */
        $formulaireService = $container->get(FormulaireService::class);
        $hydrator = $container->get('HydratorManager')->get(CampagneConfigurationRecopieHydrator::class);

        $form = new CampagneConfigurationRecopieForm();
        $form->setHydrator($hydrator);
        $form->setFormulaireService($formulaireService);
        return $form;
    }
}