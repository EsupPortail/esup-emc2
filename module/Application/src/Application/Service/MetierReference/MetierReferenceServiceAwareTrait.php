<?php

namespace Application\Service\MetierReference;

trait MetierReferenceServiceAwareTrait {

    /** @var MetierReferenceService */
    private $metierReferenceService;

    /**
     * @return MetierReferenceService
     */
    public function getMetierReferenceService()
    {
        return $this->metierReferenceService;
    }

    /**
     * @param MetierReferenceService $metierReferenceService
     * @return MetierReferenceService
     */
    public function setMetierReferenceService(MetierReferenceService $metierReferenceService)
    {
        $this->metierReferenceService = $metierReferenceService;
        return $this->metierReferenceService;
    }


}