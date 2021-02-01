<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

trait EntretienProfessionnelServiceAwareTrait {

    /** @var EntretienProfessionnelService $entretienProfessionnelService */
    private $entretienProfessionnelService;

    /**
     * @return EntretienProfessionnelService
     */
    public function getEntretienProfessionnelService() : EntretienProfessionnelService
    {
        return $this->entretienProfessionnelService;
    }

    /**
     * @param EntretienProfessionnelService $entretienProfessionnelService
     * @return EntretienProfessionnelService
     */
    public function setEntretienProfessionnelService(EntretienProfessionnelService $entretienProfessionnelService) : EntretienProfessionnelService
    {
        $this->entretienProfessionnelService = $entretienProfessionnelService;
        return $this->entretienProfessionnelService;
    }


}