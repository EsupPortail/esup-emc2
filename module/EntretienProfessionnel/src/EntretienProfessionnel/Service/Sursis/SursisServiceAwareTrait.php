<?php

namespace EntretienProfessionnel\Service\Sursis;

trait SursisServiceAwareTrait {

    /** @var SursisService */
    private $sursisService;

    /**
     * @return SursisService
     */
    public function getSursisService(): SursisService
    {
        return $this->sursisService;
    }

    /**
     * @param SursisService $sursisService
     * @return SursisService
     */
    public function setSursisService(SursisService $sursisService): SursisService
    {
        $this->sursisService = $sursisService;
        return $this->sursisService;
    }


}