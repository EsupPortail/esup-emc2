<?php

namespace Carriere\Service\Grade;

trait GradeServiceAwareTrait {

    /** @var GradeService */
    private $gradeService;

    /**
     * @return GradeService
     */
    public function getGradeService() : GradeService
    {
        return $this->gradeService;
    }

    /**
     * @param GradeService $gradeService
     * @return GradeService
     */
    public function setGradeService(GradeService $gradeService) : GradeService
    {
        $this->gradeService = $gradeService;
        return $this->gradeService;
    }

}
