<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use UnicaenAutoform\Service\Formulaire\FormulaireService;
use Interop\Container\ContainerInterface;

class ConfigurationRecopieFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationRecopieForm
     */
    public function __invoke(ContainerInterface $container) : ConfigurationRecopieForm
    {
        /**
         * @var FormulaireService $formulaireService
         */
        $formulaireService = $container->get(FormulaireService::class);

        /** @var ConfigurationRecopieHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ConfigurationRecopieHydrator::class);

        /** @var ConfigurationRecopieForm $form */
        $form = new ConfigurationRecopieForm();
        $form->setFormulaireService($formulaireService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
