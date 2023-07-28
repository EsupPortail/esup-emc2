<?php

namespace Formation\Form\EnqueteQuestion;

trait EnqueteQuestionFormAwareTrait {

    private EnqueteQuestionForm $enqueteQuestionForm;

    public function getEnqueteQuestionForm(): EnqueteQuestionForm
    {
        return $this->enqueteQuestionForm;
    }

    public function setEnqueteQuestionForm(EnqueteQuestionForm $enqueteQuestionForm): void
    {
        $this->enqueteQuestionForm = $enqueteQuestionForm;
    }

}