<?php

namespace Formation\Service\Formateur;

trait FormateurServiceAwareTrait
{
    private FormateurService $formateurService;

    public function getFormateurService(): FormateurService
    {
        return $this->formateurService;
    }

    public function setFormateurService(FormateurService $formateurService): void
    {
        $this->formateurService = $formateurService;
    }

}