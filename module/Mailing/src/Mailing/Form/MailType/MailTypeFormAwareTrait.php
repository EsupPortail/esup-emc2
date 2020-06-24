<?php

namespace Mailing\Form\MailType;

trait MailTypeFormAwareTrait {

    /** @var MailTypeForm */
    private $mailTypeForm;

    /**
     * @return MailTypeForm
     */
    public function getMailTypeForm()
    {
        return $this->mailTypeForm;
    }

    /**
     * @param MailTypeForm $mailTypeForm
     * @return MailTypeForm
     */
    public function setMailTypeForm($mailTypeForm)
    {
        $this->mailTypeForm = $mailTypeForm;
        return $this->mailTypeForm;
    }
}