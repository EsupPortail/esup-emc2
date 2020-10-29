<?php

namespace Application\Service\Domaine;

trait DomaineServiceAwareTrait {

    /** @var DomaineService $domaineService */
    private $domaineService;

    /**
     * @return DomaineService
     */
    public function getDomaineService() : DomaineService
    {
        return $this->domaineService;
    }

    /**
     * @param DomaineService $domaineService
     * @return DomaineService
     */
    public function setDomaineService(DomaineService $domaineService) : DomaineService
    {
        $this->domaineService = $domaineService;
        return $this->domaineService;
    }
}