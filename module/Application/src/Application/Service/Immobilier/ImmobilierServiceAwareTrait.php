<?php

namespace Application\Service\Immobilier;

trait ImmobilierServiceAwareTrait {

    /** @var ImmobilierService */
    private $immobilierService;

    /**
     * @return ImmobilierService
     */
    public function getImmobilierService()
    {
        return $this->immobilierService;
    }

    /**
     * @param ImmobilierService $immobilierService
     * @return ImmobilierService
     */
    public function setImmobilierService($immobilierService)
    {
        $this->immobilierService = $immobilierService;
        return $this->immobilierService;
    }


}