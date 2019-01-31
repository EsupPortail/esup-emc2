<?php

namespace Octopus\Service\Immobilier;

trait ImmobilierServiceAwareTrait {

    /** @@var ImmobilierService */
    private $immobiliserService;

    /**
     * @return ImmobilierService
     */
    public function getImmobiliserService()
    {
        return $this->immobiliserService;
    }

    /**
     * @param ImmobilierService $immobiliserService
     * @return ImmobilierService
     */
    public function setImmobiliserService($immobiliserService)
    {
        $this->immobiliserService = $immobiliserService;
        return $this->immobiliserService;
    }


}