<?php

namespace Referentiel\Service\SqlHelper;

use Psr\Container\ContainerInterface;

class SqlHelperServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SqlHelperService
     */
    public function __invoke(ContainerInterface $container) : SqlHelperService
    {
        $service = new SqlHelperService();
        return $service;
    }
}