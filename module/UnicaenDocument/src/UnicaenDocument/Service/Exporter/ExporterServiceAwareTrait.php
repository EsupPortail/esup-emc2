<?php

namespace UnicaenDocument\Service\Exporter;

trait ExporterServiceAwareTrait {

    /** @var ExporterService */
    private $exporterService;

    /**
     * @return ExporterService
     */
    public function getExporterService(): ExporterService
    {
        return $this->exporterService;
    }

    /**
     * @param ExporterService $exporterService
     * @return ExporterService
     */
    public function setExporterService(ExporterService $exporterService): ExporterService
    {
        $this->exporterService = $exporterService;
        return $this->exporterService;
    }


}