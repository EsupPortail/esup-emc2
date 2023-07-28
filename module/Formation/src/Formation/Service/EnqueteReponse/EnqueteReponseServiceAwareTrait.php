<?php

namespace Formation\Service\EnqueteReponse;

trait EnqueteReponseServiceAwareTrait
{
    private EnqueteReponseService $enqueteReponseService;

    public function getEnqueteReponseService(): EnqueteReponseService
    {
        return $this->enqueteReponseService;
    }

    public function setEnqueteReponseService(EnqueteReponseService $enqueteReponseService): void
    {
        $this->enqueteReponseService = $enqueteReponseService;
    }

}