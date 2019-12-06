<?php

namespace Application\Service\CompetencesRetirees;

trait CompetencesRetireesServiceAwareTrait {

    /** @var CompetencesRetireesService $competencesConserveesService */
    private $competencesConserveesService;

    /**
     * @return CompetencesRetireesService
     */
    public function getCompetencesRetireesService()
    {
        return $this->competencesConserveesService;
    }

    /**
     * @param CompetencesRetireesService $competencesConserveesService
     * @return CompetencesRetireesService
     */
    public function setCompetencesRetireesService($competencesConserveesService)
    {
        $this->competencesConserveesService = $competencesConserveesService;
        return $this->competencesConserveesService;
    }

}