<?php

namespace Element\View\Helper;


use Psr\Container\ContainerInterface;

class CompetencesViewHelperFactory {

    public function __invoke(ContainerInterface $container) : CompetencesViewHelper
    {
        $helper = new CompetencesViewHelper();
        return $helper;
    }
}