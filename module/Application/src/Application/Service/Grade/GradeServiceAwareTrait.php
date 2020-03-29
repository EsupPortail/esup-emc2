<?php

namespace Application\Service\Grade;

trait GradeServiceAwareTrait {

    /** @var GradeService */
    private $gradeService;

    /**
     * @return GradeService
     */
    public function getGradeService()
    {
        return $this->gradeService;
    }

    /**
     * @param GradeService $gradeService
     * @return GradeService
     */
    public function setGradeService($gradeService)
    {
        $this->gradeService = $gradeService;
        return $this->gradeService;
    }

}
