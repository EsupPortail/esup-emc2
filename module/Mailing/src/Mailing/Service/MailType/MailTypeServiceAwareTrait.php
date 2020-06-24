<?php

namespace Mailing\Service\MailType;

trait MailTypeServiceAwareTrait {

    /** @var MailTypeService */
    private $mailTypeService;

    /**
     * @return MailTypeService
     */
    public function getMailTypeService()
    {
        return $this->mailTypeService;
    }

    /**
     * @param MailTypeService $mailTypeService
     * @return MailTypeService
     */
    public function setMailTypeService($mailTypeService)
    {
        $this->mailTypeService = $mailTypeService;
        return $this->mailTypeService;
    }


}