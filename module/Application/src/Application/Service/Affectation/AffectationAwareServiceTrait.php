<?php

namespace Application\Service\Affectation;

Trait AffectationAwareServiceTrait {

    /** @var AffectationService $affectationSerive */
    private $affectationService;

    /**
     * @return AffectationService
     */
    public function getAffectationService()
    {
        return $this->affectationService;
    }

    /**
     * @param AffectationService $affectationService
     * @return  AffectationService
     */
    public function setAffectationService($affectationService)
    {
        $this->affectationService = $affectationService;
        return $this->affectationService;
    }

}