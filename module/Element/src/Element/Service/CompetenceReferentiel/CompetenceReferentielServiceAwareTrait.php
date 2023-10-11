<?php

namespace Element\Service\CompetenceReferentiel;

trait CompetenceReferentielServiceAwareTrait {

    private CompetenceReferentielService $competenceReferentielService;

    public function getCompetenceReferentielService(): CompetenceReferentielService
    {
        return $this->competenceReferentielService;
    }

    public function setCompetenceReferentielService(CompetenceReferentielService $competenceReferentielService): void
    {
        $this->competenceReferentielService = $competenceReferentielService;
    }

}