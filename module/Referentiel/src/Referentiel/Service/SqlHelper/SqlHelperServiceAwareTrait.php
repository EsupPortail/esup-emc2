<?php

namespace Referentiel\Service\SqlHelper;

trait SqlHelperServiceAwareTrait
{

    private SqlHelperService $sqlHelperService;

    public function getSqlHelperService(): SqlHelperService
    {
        return $this->sqlHelperService;
    }

    public function setSqlHelperService(SqlHelperService $sqlHelperService): void
    {
        $this->sqlHelperService = $sqlHelperService;
    }

}