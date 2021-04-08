<?php

namespace Application\Service\MaitriseNiveau;

trait MaitriseNiveauServiceAwareTrait {

    /** @var MaitriseNiveauService */
    private $MaitriseNiveauService;

    /**
     * @return MaitriseNiveauService
     */
    public function getMaitriseNiveauService(): MaitriseNiveauService
    {
        return $this->MaitriseNiveauService;
    }

    /**
     * @param MaitriseNiveauService $MaitriseNiveauService
     * @return MaitriseNiveauService
     */
    public function setMaitriseNiveauService(MaitriseNiveauService $MaitriseNiveauService): MaitriseNiveauService
    {
        $this->MaitriseNiveauService = $MaitriseNiveauService;
        return $this->MaitriseNiveauService;
    }


}