<?php

namespace Element\Service\CompetenceElement;

trait CompetenceElementServiceAwareTrait {

    /** @var CompetenceElementService */
    private $competenceElementService;

    /**
     * @return CompetenceElementService
     */
    public function getCompetenceElementService(): CompetenceElementService
    {
        return $this->competenceElementService;
    }

    /**
     * @param CompetenceElementService $competenceElementService
     * @return CompetenceElementService
     */
    public function setCompetenceElementService(CompetenceElementService $competenceElementService): CompetenceElementService
    {
        $this->competenceElementService = $competenceElementService;
        return $this->competenceElementService;
    }


}