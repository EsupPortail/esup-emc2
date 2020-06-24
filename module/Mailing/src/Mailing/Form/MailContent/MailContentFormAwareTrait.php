<?php

namespace Mailing\Form\MailContent;

trait MailContentFormAwareTrait {

    /** @var MailContentForm */
    private $mailContentForm;

    /**
     * @return MailContentForm
     */
    public function getMailContentForm()
    {
        return $this->mailContentForm;
    }

    /**
     * @param MailContentForm $mailContentForm
     * @return MailContentForm
     */
    public function setMailContentForm($mailContentForm)
    {
        $this->mailContentForm = $mailContentForm;
        return $this->mailContentForm;
    }

}