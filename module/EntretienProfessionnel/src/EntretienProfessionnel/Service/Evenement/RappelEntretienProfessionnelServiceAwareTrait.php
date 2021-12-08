<?php

namespace EntretienProfessionnel\Service\Evenement;

trait RappelEntretienProfessionnelServiceAwareTrait {

    /** @var RappelEntretienProfessionnelService */
    private $rappelEntretienProfessionnelService;

    /**
     * @return RappelEntretienProfessionnelService
     */
    public function getRappelEntretienProfessionnelService(): RappelEntretienProfessionnelService
    {
        return $this->rappelEntretienProfessionnelService;
    }

    /**
     * @param RappelEntretienProfessionnelService $rappelEntretienProfessionnelService
     * @return RappelEntretienProfessionnelService
     */
    public function setRappelEntretienProfessionnelService(RappelEntretienProfessionnelService $rappelEntretienProfessionnelService): RappelEntretienProfessionnelService
    {
        $this->rappelEntretienProfessionnelService = $rappelEntretienProfessionnelService;
        return $this->rappelEntretienProfessionnelService;
    }
}