<?php

namespace Metier\Service\Reference;

trait ReferenceServiceAwareTrait {

    /** @var ReferenceService */
    private $metierReferenceService;

    /**
     * @return ReferenceService
     */
    public function getReferenceService() : ReferenceService
    {
        return $this->metierReferenceService;
    }

    /**
     * @param ReferenceService $metierReferenceService
     * @return ReferenceService
     */
    public function setReferenceService(ReferenceService $metierReferenceService) : ReferenceService
    {
        $this->metierReferenceService = $metierReferenceService;
        return $this->metierReferenceService;
    }


}