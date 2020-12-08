<?php

namespace UnicaenNote\Service\Note;

trait NoteServiceAwareTrait {

    /** @var NoteService */
    private $noteService;

    /**
     * @return NoteService
     */
    public function getNoteService(): NoteService
    {
        return $this->noteService;
    }

    /**
     * @param NoteService $noteService
     * @return NoteService
     */
    public function setNoteService(NoteService $noteService): NoteService
    {
        $this->noteService = $noteService;
        return $this->noteService;
    }

}