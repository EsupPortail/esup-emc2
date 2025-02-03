<?php

namespace UnicaenContact\Form\Contact;

trait ContactFormAwareTrait
{
    private ContactForm $contactForm;

    public function getContactForm(): ContactForm
    {
        return $this->contactForm;
    }

    public function setContactForm(ContactForm $contactForm): void
    {
        $this->contactForm = $contactForm;
    }


}