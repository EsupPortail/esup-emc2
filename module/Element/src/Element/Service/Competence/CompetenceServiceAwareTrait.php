<?php

namespace Element\Service\Competence;

trait CompetenceServiceAwareTrait {

    private CompetenceService $competenceService;

    public function getCompetenceService() : CompetenceService
    {
        return $this->competenceService;
    }

    public function setCompetenceService(CompetenceService $competenceService) : void
    {
        $this->competenceService = $competenceService;
    }
}