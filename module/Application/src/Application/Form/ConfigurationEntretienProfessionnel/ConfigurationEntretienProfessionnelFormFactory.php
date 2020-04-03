<?php

namespace Application\Form\ConfigurationEntretienProfessionnel;

use Autoform\Service\Formulaire\FormulaireService;
use Interop\Container\ContainerInterface;

class ConfigurationEntretienProfessionnelFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationEntretienProfessionnelForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormulaireService $formulaireService
         */
        $formulaireService = $container->get(FormulaireService::class);

        /** @var ConfigurationEntretienProfessionnelHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ConfigurationEntretienProfessionnelHydrator::class);

        /** @var ConfigurationEntretienProfessionnelForm $form */
        $form = new ConfigurationEntretienProfessionnelForm();
        $form->setFormulaireService($formulaireService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
