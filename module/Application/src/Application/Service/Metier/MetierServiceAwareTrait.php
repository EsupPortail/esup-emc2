<?php

namespace  Application\Service\Metier;

trait MetierServiceAwareTrait {

    /** @var MetierService */
    private $metierService;

    /**
     * @return MetierService
     */
    public function getMetierService()
    {
        return $this->metierService;
    }

    /**
     * @param MetierService $metierService
     * @return MetierService
     */
    public function setMetierService($metierService)
    {
        $this->metierService = $metierService;
        return $this->metierService;
    }
}