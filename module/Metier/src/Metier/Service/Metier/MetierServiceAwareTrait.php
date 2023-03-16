<?php

namespace Metier\Service\Metier;

trait MetierServiceAwareTrait {

    private MetierService $metierService;

    public function getMetierService() : MetierService
    {
        return $this->metierService;
    }

    public function setMetierService(MetierService $metierService) : void
    {
        $this->metierService = $metierService;
    }


}