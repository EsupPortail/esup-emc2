<?php

namespace Element\View\Helper;


use Psr\Container\ContainerInterface;

class ApplicationsViewHelperFactory {

    public function __invoke(ContainerInterface $container) : ApplicationsViewHelper
    {
        $helper = new ApplicationsViewHelper();
        return $helper;
    }
}