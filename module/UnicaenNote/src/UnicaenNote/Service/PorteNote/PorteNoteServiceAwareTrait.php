<?php

namespace UnicaenNote\Service\PorteNote;

trait PorteNoteServiceAwareTrait {

    /** @var PorteNoteService */
    private $porteNoteService;

    /**
     * @return PorteNoteService
     */
    public function getPorteNoteService(): PorteNoteService
    {
        return $this->porteNoteService;
    }

    /**
     * @param PorteNoteService $porteNoteService
     * @return PorteNoteService
     */
    public function setPorteNoteService(PorteNoteService $porteNoteService): PorteNoteService
    {
        $this->porteNoteService = $porteNoteService;
        return $this->porteNoteService;
    }

}