<?php

namespace Formation\Service\Domaine;

trait DomaineServiceAwareTrait {

    private DomaineService $domaineService;

    public function getDomaineService(): DomaineService
    {
        return $this->domaineService;
    }

    public function setDomaineService(DomaineService $domaineService): void
    {
        $this->domaineService = $domaineService;
    }

}