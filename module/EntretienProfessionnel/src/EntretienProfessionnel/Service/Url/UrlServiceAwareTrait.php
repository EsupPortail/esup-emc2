<?php

namespace EntretienProfessionnel\Service\Url;

use Application\Service\Url\UrlService as UrlServiceBase;

trait UrlServiceAwareTrait {

    private UrlService $urlService;

    public function getUrlService(): UrlService
    {
        return $this->urlService;
    }

    public function setUrlService(UrlServiceBase $urlService): UrlService
    {
        $this->urlService = $urlService;
        return $this->urlService;
    }
}