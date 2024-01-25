<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

trait ConfigurationRecopieFormAwareTrait {

    private ConfigurationRecopieForm $configurationRecopieForm;

    public function getConfigurationRecopieForm() : ConfigurationRecopieForm
    {
        return $this->configurationRecopieForm;
    }

    public function setConfigurationRecopieForm(ConfigurationRecopieForm $configurationRecopieForm) : void
    {
        $this->configurationRecopieForm = $configurationRecopieForm;
    }

}