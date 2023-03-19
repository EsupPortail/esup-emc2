<?php

namespace Carriere\Service\Grade;

trait GradeServiceAwareTrait {

    private GradeService $gradeService;

    public function getGradeService() : GradeService
    {
        return $this->gradeService;
    }

    public function setGradeService(GradeService $gradeService) : void
    {
        $this->gradeService = $gradeService;
    }

}
