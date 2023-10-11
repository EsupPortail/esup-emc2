<?php

namespace Formation\Service\EnqueteCategorie;

trait EnqueteCategorieServiceAwareTrait
{
    private EnqueteCategorieService $enqueteCategorieService;

    public function getEnqueteCategorieService(): EnqueteCategorieService
    {
        return $this->enqueteCategorieService;
    }

    public function setEnqueteCategorieService(EnqueteCategorieService $enqueteCategorieService): void
    {
        $this->enqueteCategorieService = $enqueteCategorieService;
    }

}