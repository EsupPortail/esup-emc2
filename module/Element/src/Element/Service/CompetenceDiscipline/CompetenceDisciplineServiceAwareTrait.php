<?php

namespace Element\Service\CompetenceDiscipline;

trait CompetenceDisciplineServiceAwareTrait {

    /** @var CompetenceDisciplineService $competenceDisciplineService */
    private CompetenceDisciplineService $competenceDisciplineService;

    public function getCompetenceDisciplineService(): CompetenceDisciplineService
    {
        return $this->competenceDisciplineService;
    }

    public function setCompetenceDisciplineService(CompetenceDisciplineService $competenceDisciplineService): void
    {
        $this->competenceDisciplineService = $competenceDisciplineService;
    }

}