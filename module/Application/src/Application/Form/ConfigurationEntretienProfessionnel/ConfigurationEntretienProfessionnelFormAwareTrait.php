<?php

namespace Application\Form\ConfigurationEntretienProfessionnel;

trait ConfigurationEntretienProfessionnelFormAwareTrait {

    /** @var ConfigurationEntretienProfessionnelForm */
    private $configurationEntretienProfessionnelForm;

    /**
     * @return ConfigurationEntretienProfessionnelForm
     */
    public function getConfigurationEntretienProfessionnelForm()
    {
        return $this->configurationEntretienProfessionnelForm;
    }

    /**
     * @param ConfigurationEntretienProfessionnelForm $configurationEntretienProfessionnelForm
     * @return ConfigurationEntretienProfessionnelForm
     */
    public function setConfigurationEntretienProfessionnelForm($configurationEntretienProfessionnelForm)
    {
        $this->configurationEntretienProfessionnelForm = $configurationEntretienProfessionnelForm;
        return $this->configurationEntretienProfessionnelForm;
    }

}