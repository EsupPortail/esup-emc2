<?php

namespace Metier\Service\Metier;

trait MetierServiceAwareTrait {

    /** @var MetierService */
    private $metierService;

    /**
     * @return MetierService
     */
    public function getMetierService() : MetierService
    {
        return $this->metierService;
    }

    /**
     * @param MetierService $metierService
     * @return MetierService
     */
    public function setMetierService(MetierService $metierService) : MetierService
    {
        $this->metierService = $metierService;
        return $this->metierService;
    }


}