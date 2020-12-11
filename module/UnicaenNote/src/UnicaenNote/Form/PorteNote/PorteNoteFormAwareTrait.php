<?php

namespace UnicaenNote\Form\PorteNote;

trait PorteNoteFormAwareTrait {

    /** @var PorteNoteForm */
    private $porteNoteForm;

    /**
     * @return PorteNoteForm
     */
    public function getPorteNoteForm(): PorteNoteForm
    {
        return $this->porteNoteForm;
    }

    /**
     * @param PorteNoteForm $porteNoteForm
     * @return PorteNoteForm
     */
    public function setPorteNoteForm(PorteNoteForm $porteNoteForm): PorteNoteForm
    {
        $this->porteNoteForm = $porteNoteForm;
        return $this->porteNoteForm;
    }
}