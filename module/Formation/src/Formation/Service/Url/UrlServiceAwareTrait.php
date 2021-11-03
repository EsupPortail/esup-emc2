<?php

namespace Formation\Service\Url;

trait UrlServiceAwareTrait {

    /** @var UrlService */
    private $urlService;

    /**
     * @return UrlService
     */
    public function getUrlService(): UrlService
    {
        return $this->urlService;
    }

    /**
     * @param UrlService $urlService
     * @return UrlService
     */
    public function setUrlService(UrlService $urlService): UrlService
    {
        $this->urlService = $urlService;
        return $this->urlService;
    }
}