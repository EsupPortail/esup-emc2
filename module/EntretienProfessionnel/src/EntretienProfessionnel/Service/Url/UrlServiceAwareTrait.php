<?php

namespace EntretienProfessionnel\Service\Url;

trait UrlServiceAwareTrait {

    private UrlService $urlService;

    public function getUrlService(): UrlService
    {
        return $this->urlService;
    }

    public function setUrlService(UrlService $urlService): UrlService
    {
        $this->urlService = $urlService;
        return $this->urlService;
    }
}