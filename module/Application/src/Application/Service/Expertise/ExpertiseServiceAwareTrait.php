<?php

namespace Application\Service\Expertise;

trait ExpertiseServiceAwareTrait {

    /** @var ExpertiseService */
    private $expertiseService;

    /**
     * @return ExpertiseService
     */
    public function getExpertiseService()
    {
        return $this->expertiseService;
    }

    /**
     * @param ExpertiseService $expertiseService
     * @return ExpertiseService
     */
    public function setExpertiseService($expertiseService)
    {
        $this->expertiseService = $expertiseService;
        return $this->expertiseService;
    }
}
