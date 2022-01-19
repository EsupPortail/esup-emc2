<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

trait ConfigurationRecopieFormAwareTrait {

    /** @var ConfigurationRecopieForm */
    private $configurationRecopieForm;

    /**
     * @return ConfigurationRecopieForm
     */
    public function getConfigurationRecopieForm() : ConfigurationRecopieForm
    {
        return $this->configurationRecopieForm;
    }

    /**
     * @param ConfigurationRecopieForm $configurationRecopieForm
     * @return ConfigurationRecopieForm
     */
    public function setConfigurationRecopieForm(ConfigurationRecopieForm $configurationRecopieForm) : ConfigurationRecopieForm
    {
        $this->configurationRecopieForm = $configurationRecopieForm;
        return $this->configurationRecopieForm;
    }

}