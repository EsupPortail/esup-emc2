<?php

namespace UnicaenContact\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ContactPrivileges extends Privileges
{
    const CONTACT_INDEX = 'contact-contact_index';
    const CONTACT_AFFICHER = 'contact-contact_afficher';
    const CONTACT_AJOUTER = 'contact-contact_ajouter';
    const CONTACT_MODIFIER = 'contact-contact_modifier';
    const CONTACT_HISTORISER = 'contact-contact_historiser';
    const CONTACT_SUPPRIMER = 'contact-contact_supprimer';
}