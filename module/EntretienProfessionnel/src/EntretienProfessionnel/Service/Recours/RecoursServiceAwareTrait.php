<?php

namespace EntretienProfessionnel\Service\Recours;

trait RecoursServiceAwareTrait
{
    private RecoursService $recoursService;

    public function getRecoursService(): RecoursService
    {
        return $this->recoursService;
    }

    public function setRecoursService(RecoursService $recoursService): void
    {
        $this->recoursService = $recoursService;
    }

}