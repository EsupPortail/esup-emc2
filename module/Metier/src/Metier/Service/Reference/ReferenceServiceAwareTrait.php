<?php

namespace Metier\Service\Reference;

trait ReferenceServiceAwareTrait {

    private ReferenceService $metierReferenceService;

    public function getReferenceService() : ReferenceService
    {
        return $this->metierReferenceService;
    }

    public function setReferenceService(ReferenceService $metierReferenceService) : void
    {
        $this->metierReferenceService = $metierReferenceService;
    }


}