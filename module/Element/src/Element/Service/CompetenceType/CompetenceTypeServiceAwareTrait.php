<?php

namespace Element\Service\CompetenceType;

trait CompetenceTypeServiceAwareTrait {

    /** @var CompetenceTypeService $competenceTypeService */
    private $competenceTypeService;

    /**
     * @return CompetenceTypeService
     */
    public function getCompetenceTypeService() : CompetenceTypeService
    {
        return $this->competenceTypeService;
    }

    /**
     * @param CompetenceTypeService $competenceTypeService
     * @return CompetenceTypeService
     */
    public function setCompetenceTypeService(CompetenceTypeService $competenceTypeService) : CompetenceTypeService
    {
        $this->competenceTypeService = $competenceTypeService;
        return $this->competenceTypeService;
    }
}