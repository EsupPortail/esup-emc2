<?php

namespace Application\Service\Poste;

trait PosteServiceAwareTrait {

    /** @var PosteService */
    private $posteService;

    /**
     * @return PosteService
     */
    public function getPosteService()
    {
        return $this->posteService;
    }

    /**
     * @param PosteService $posteService
     * @return PosteService
     */
    public function setPosteService($posteService)
    {
        $this->posteService = $posteService;
        return $this->posteService;
    }

}