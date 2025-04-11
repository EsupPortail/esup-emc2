<?php

namespace FicheMetier\Service\Import;

trait ImportServiceAwareTrait
{

    private ImportService $importService;

    public function getImportService(): ImportService
    {
        return $this->importService;
    }

    public function setImportService(ImportService $importService): void
    {
        $this->importService = $importService;
    }

}