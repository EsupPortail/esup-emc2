<?php

namespace Element\View\Helper;


use Psr\Container\ContainerInterface;

class CompetenceDisciplineArrayViewHelperFactory {

    public function __invoke(ContainerInterface $container) : CompetenceDisciplineArrayViewHelper
    {
        $helper = new CompetenceDisciplineArrayViewHelper();
        return $helper;
    }
}