<?php

namespace Formation\Service\Seance;


trait SeanceServiceAwareTrait
{

    private SeanceService $seanceService;

    public function getSeanceService() : SeanceService
    {
        return $this->seanceService;
    }

    public function setSeanceService(SeanceService $seanceService) : void
    {
        $this->seanceService = $seanceService;
    }

}