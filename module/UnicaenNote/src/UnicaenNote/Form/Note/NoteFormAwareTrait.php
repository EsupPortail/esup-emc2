<?php

namespace UnicaenNote\Form\Note;

trait NoteFormAwareTrait {

    /** @var NoteForm */
    private $noteForm;

    /**
     * @return NoteForm
     */
    public function getNoteForm(): NoteForm
    {
        return $this->noteForm;
    }

    /**
     * @param NoteForm $noteForm
     * @return NoteForm
     */
    public function setNoteForm(NoteForm $noteForm): NoteForm
    {
        $this->noteForm = $noteForm;
        return $this->noteForm;
    }

}