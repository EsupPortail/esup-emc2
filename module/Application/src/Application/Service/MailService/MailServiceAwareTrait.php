<?php

namespace Application\Service\MailService;

trait MailServiceAwareTrait
{
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @param MailService $mailService
     * @return self
     */
    public function setMailService(MailService $mailService)
    {
        $this->mailService = $mailService;
        return $this;
    }

    /**
     * @return MailService
     */
    public function getMailService()
    {
        return $this->mailService;
    }
}