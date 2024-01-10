<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

trait EntretienProfessionnelServiceAwareTrait
{
    private EntretienProfessionnelService $entretienProfessionnelService;

    public function getEntretienProfessionnelService(): EntretienProfessionnelService
    {
        return $this->entretienProfessionnelService;
    }

    public function setEntretienProfessionnelService(EntretienProfessionnelService $entretienProfessionnelService): EntretienProfessionnelService
    {
        $this->entretienProfessionnelService = $entretienProfessionnelService;
        return $this->entretienProfessionnelService;
    }
}