<?php

namespace Application\Service\CompetenceMaitrise;

trait CompetenceMaitriseServiceAwareTrait {

    /** @var CompetenceMaitriseService */
    private $competenceMaitriseService;

    /**
     * @return CompetenceMaitriseService
     */
    public function getCompetenceMaitriseService(): CompetenceMaitriseService
    {
        return $this->competenceMaitriseService;
    }

    /**
     * @param CompetenceMaitriseService $competenceMaitriseService
     * @return CompetenceMaitriseService
     */
    public function setCompetenceMaitriseService(CompetenceMaitriseService $competenceMaitriseService): CompetenceMaitriseService
    {
        $this->competenceMaitriseService = $competenceMaitriseService;
        return $this->competenceMaitriseService;
    }


}