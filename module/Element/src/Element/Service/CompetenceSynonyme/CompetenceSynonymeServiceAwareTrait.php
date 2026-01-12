<?php

namespace Element\Service\CompetenceSynonyme;

trait CompetenceSynonymeServiceAwareTrait
{

    protected CompetenceSynonymeService $competenceSynonymeService;

    public function getCompetenceSynonymeService(): CompetenceSynonymeService
    {
        return $this->competenceSynonymeService;
    }

    public function setCompetenceSynonymeService(CompetenceSynonymeService $competenceSynonymeService): void
    {
        $this->competenceSynonymeService = $competenceSynonymeService;
    }


}
