<?php

namespace Formation\Service\Url;

trait UrlServiceAwareTrait {

    private UrlService $urlService;

    public function getUrlService(): UrlService
    {
        return $this->urlService;
    }

    public function setUrlService(UrlService $urlService): void
    {
        $this->urlService = $urlService;
    }
}