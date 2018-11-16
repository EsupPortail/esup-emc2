<?php

namespace Mailing\Service\Mailing;

trait MailingServiceAwareTrait
{
    /** @var MailingService */
    private $mailingService;

    /**
     * @return MailingService
     */
    public function getMailingService()
    {
        return $this->mailingService;
    }

    /**
     * @param MailingService $mailingService
     * @return MailingService
     */
    public function setMailingService($mailingService)
    {
        $this->mailingService = $mailingService;
        return $this->mailingService;
    }
}