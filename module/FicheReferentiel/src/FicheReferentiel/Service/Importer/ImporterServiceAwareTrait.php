<?php

namespace FicheReferentiel\Service\Importer;

trait ImporterServiceAwareTrait
{
    private ImporterService $importerService;

    public function getImporterService(): ImporterService
    {
        return $this->importerService;
    }

    public function setImporterService(ImporterService $importerService): void
    {
        $this->importerService = $importerService;
    }

}