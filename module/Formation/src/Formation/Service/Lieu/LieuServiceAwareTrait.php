<?php

namespace Formation\Service\Lieu;

trait LieuServiceAwareTrait {

    private LieuService $lieuService;

    public function getLieuService(): LieuService
    {
        return $this->lieuService;
    }

    public function setLieuService(LieuService $lieuService): void
    {
        $this->lieuService = $lieuService;
    }

}