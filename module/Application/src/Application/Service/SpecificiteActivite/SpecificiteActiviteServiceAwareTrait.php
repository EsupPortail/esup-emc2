<?php

namespace Application\Service\SpecificiteActivite;

trait SpecificiteActiviteServiceAwareTrait {

    /** @var SpecificiteActiviteService */
    private $specificiteActiviteService;

    /**
     * @return SpecificiteActiviteService
     */
    public function getSpecificiteActiviteService(): SpecificiteActiviteService
    {
        return $this->specificiteActiviteService;
    }

    /**
     * @param SpecificiteActiviteService $specificiteActiviteService
     * @return SpecificiteActiviteService
     */
    public function setSpecificiteActiviteService(SpecificiteActiviteService $specificiteActiviteService): SpecificiteActiviteService
    {
        $this->specificiteActiviteService = $specificiteActiviteService;
        return $this->specificiteActiviteService;
    }
}