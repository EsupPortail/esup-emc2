<?php

namespace Application\Form\ConfigurationFicheMetier;

trait ConfigurationFicheMetierFormAwareTrait {

    /** @var ConfigurationFicheMetierForm $configurationFicheMetierForm */
    private $configurationFicheMetierForm;

    /**
     * @return ConfigurationFicheMetierForm
     */
    public function getConfigurationFicheMetierForm()
    {
        return $this->configurationFicheMetierForm;
    }

    /**
     * @param ConfigurationFicheMetierForm $configurationFicheMetierForm
     * @return ConfigurationFicheMetierForm
     */
    public function setConfigurationFicheMetierForm($configurationFicheMetierForm)
    {
        $this->configurationFicheMetierForm = $configurationFicheMetierForm;
        return $this->configurationFicheMetierForm;
    }


}