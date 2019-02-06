<?php

namespace Application\Form\RessourceRh;

trait GradeFormAwareTrait {

    /** @var GradeForm $gradeForm */
    private $gradeForm;

    /**
     * @return GradeForm
     */
    public function getGradeForm()
    {
        return $this->gradeForm;
    }

    /**
     * @param GradeForm $gradeForm
     * @return GradeForm
     */
    public function setGradeForm($gradeForm)
    {
        $this->gradeForm = $gradeForm;
        return $this->gradeForm;
    }


}