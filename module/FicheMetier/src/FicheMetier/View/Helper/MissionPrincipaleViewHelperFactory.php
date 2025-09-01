<?php

namespace FicheMetier\View\Helper;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleViewHelperFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionPrincipaleViewHelper
    {
        $helper = new MissionPrincipaleViewHelper();
        return $helper;
    }
}