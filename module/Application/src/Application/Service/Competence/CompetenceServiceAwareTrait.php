<?php

namespace Application\Service\Competence;

trait CompetenceServiceAwareTrait {

    /** @var CompetenceService $competenceService */
    private $competenceService;

    /**
     * @return CompetenceService
     */
    public function getCompetenceService()
    {
        return $this->competenceService;
    }

    /**
     * @param CompetenceService $competenceService
     * @return CompetenceService
     */
    public function setCompetenceService($competenceService)
    {
        $this->competenceService = $competenceService;
        return $this->competenceService;
    }
}