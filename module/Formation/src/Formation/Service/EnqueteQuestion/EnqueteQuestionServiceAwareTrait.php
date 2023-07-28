<?php

namespace Formation\Service\EnqueteQuestion;

trait EnqueteQuestionServiceAwareTrait
{
    private EnqueteQuestionService $enqueteQuestionService;

    public function getEnqueteQuestionService(): EnqueteQuestionService
    {
        return $this->enqueteQuestionService;
    }

    public function setEnqueteQuestionService(EnqueteQuestionService $enqueteQuestionService): void
    {
        $this->enqueteQuestionService = $enqueteQuestionService;
    }

}