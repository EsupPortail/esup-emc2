<?php

namespace Application\Service\ParcoursDeFormation;

trait ParcoursDeFormationServiceAwareTrait {

    /** @var ParcoursDeFormationService */
    private $parcoursDeFormationService;

    /**
     * @return ParcoursDeFormationService
     */
    public function getParcoursDeFormationService()
    {
        return $this->parcoursDeFormationService;
    }

    /**
     * @param ParcoursDeFormationService $parcoursDeFormationService
     * @return ParcoursDeFormationService
     */
    public function setParcoursDeFormationService($parcoursDeFormationService)
    {
        $this->parcoursDeFormationService = $parcoursDeFormationService;
        return $this->parcoursDeFormationService;
    }

}