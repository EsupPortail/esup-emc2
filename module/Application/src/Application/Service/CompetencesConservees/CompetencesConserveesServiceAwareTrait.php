<?php

namespace Application\Service\CompetencesConservees;

trait CompetencesConserveesServiceAwareTrait {

    /** @var CompetencesConserveesService $competencesConserveesService */
    private $competencesConserveesService;

    /**
     * @return CompetencesConserveesService
     */
    public function getCompetencesConserveesService()
    {
        return $this->competencesConserveesService;
    }

    /**
     * @param CompetencesConserveesService $competencesConserveesService
     * @return CompetencesConserveesService
     */
    public function setCompetencesConserveesService($competencesConserveesService)
    {
        $this->competencesConserveesService = $competencesConserveesService;
        return $this->competencesConserveesService;
    }

}