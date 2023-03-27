<?php

namespace Element\Service\CompetenceElement;

trait CompetenceElementServiceAwareTrait {

    private CompetenceElementService $competenceElementService;

    public function getCompetenceElementService(): CompetenceElementService
    {
        return $this->competenceElementService;
    }

    public function setCompetenceElementService(CompetenceElementService $competenceElementService): void
    {
        $this->competenceElementService = $competenceElementService;
    }

}