<?php

namespace Application\Service\Domaine;

trait DomaineServiceAwareTrait {

    /** @var DomaineService $domaineService */
    private $domaineService;

    /**
     * @return DomaineService
     */
    public function getDomaineService()
    {
        return $this->domaineService;
    }

    /**
     * @param DomaineService $domaineService
     * @return DomaineService
     */
    public function setDomaineService($domaineService)
    {
        $this->domaineService = $domaineService;
        return $this->domaineService;
    }
}