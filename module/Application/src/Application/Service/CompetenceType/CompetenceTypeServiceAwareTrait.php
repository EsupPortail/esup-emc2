<?php

namespace Application\Service\CompetenceType;

trait CompetenceTypeServiceAwareTrait {

    /** @var CompetenceTypeService $competenceTypeService */
    private $competenceTypeService;

    /**
     * @return CompetenceTypeService
     */
    public function getCompetenceTypeService()
    {
        return $this->competenceTypeService;
    }

    /**
     * @param CompetenceTypeService $competenceTypeService
     * @return CompetenceTypeService
     */
    public function setCompetenceTypeService($competenceTypeService)
    {
        $this->competenceTypeService = $competenceTypeService;
        return $this->competenceTypeService;
    }
}