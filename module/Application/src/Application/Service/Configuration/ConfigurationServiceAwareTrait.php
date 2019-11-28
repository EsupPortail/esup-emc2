<?php

namespace Application\Service\Configuration;

trait ConfigurationServiceAwareTrait {

    /** @var ConfigurationService $configurationService */
    private $configurationService;

    /**
     * @return ConfigurationService
     */
    public function getConfigurationService()
    {
        return $this->configurationService;
    }

    /**
     * @param ConfigurationService $configurationService
     * @return ConfigurationService
     */
    public function setConfigurationService($configurationService)
    {
        $this->configurationService = $configurationService;
        return $this->configurationService;
    }


}