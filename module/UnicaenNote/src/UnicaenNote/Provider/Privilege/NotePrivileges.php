<?php

namespace UnicaenNote\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class NotePrivileges extends Privileges
{
    const NOTE_INDEX = 'note-note_index';
    const NOTE_AFFICHER = 'note-note_afficher';
    const NOTE_AJOUTER = 'note-note_ajouter';
    const NOTE_MODIFIER = 'note-note_modifier';
    const NOTE_HISTORISER = 'note-note_historiser';
    const NOTE_SUPPRIMER = 'note-note_supprimer';
}