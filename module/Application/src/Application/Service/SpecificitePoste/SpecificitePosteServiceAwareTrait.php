<?php

namespace Application\Service\SpecificitePoste;

trait SpecificitePosteServiceAwareTrait {

    /** @var SpecificitePosteService */
    private  $specificitePosteService;

    /**
     * @return SpecificitePosteService
     */
    public function getSpecificitePosteService()
    {
        return $this->specificitePosteService;
    }

    /**
     * @param SpecificitePosteService $specificitePosteService
     * @return SpecificitePosteService
     */
    public function setSpecificitePosteService(SpecificitePosteService $specificitePosteService)
    {
        $this->specificitePosteService = $specificitePosteService;
        return $this->specificitePosteService;
    }

}