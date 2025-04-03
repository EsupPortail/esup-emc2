<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireService;
use Interop\Container\ContainerInterface;

class ConfigurationRecopieFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ConfigurationRecopieForm
    {
        /**
         * @var FormulaireService $formulaireService
         */
        $formulaireService = $container->get(FormulaireService::class);

        /** @var ConfigurationRecopieHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ConfigurationRecopieHydrator::class);

        $form = new ConfigurationRecopieForm();
        $form->setFormulaireService($formulaireService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
