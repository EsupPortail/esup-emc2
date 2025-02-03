<?php

namespace UnicaenContact\Service\Contact;

trait ContactServiceAwareTrait
{
    private ContactService $contactService;

    public function getContactService(): ContactService
    {
        return $this->contactService;
    }

    public function setContactService(ContactService $contactService): void
    {
        $this->contactService = $contactService;
    }


}