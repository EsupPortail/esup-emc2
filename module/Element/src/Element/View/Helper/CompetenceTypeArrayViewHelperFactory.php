<?php

namespace Element\View\Helper;


use Psr\Container\ContainerInterface;

class CompetenceTypeArrayViewHelperFactory {

    public function __invoke(ContainerInterface $container) : CompetenceTypeArrayViewHelper
    {
        $helper = new CompetenceTypeArrayViewHelper();
        return $helper;
    }
}